import React, { useState } from 'react';
import Layout from '@/Components/Layout';
import { Link, useForm, router } from '@inertiajs/react';
import { formatRs, num } from '@/Utils/math';

export default function ForecastShow({ goal, s, asOf }) {
    const asOfDateStr = typeof asOf === 'string' ? asOf.slice(0, 10) : new Date().toISOString().slice(0, 10);
    const [simDate, setSimDate] = useState(asOfDateStr);

    const initialTargets = {};
    const initialFixed = {};
    (s.cells || []).forEach((c) => {
        initialTargets[c.month] = c.target;
        initialFixed[c.month] = !!c.fixed;
    });

    const { data, setData, put, processing } = useForm({
        as_of: asOfDateStr,
        targets: initialTargets,
        fixed: initialFixed,
        action: 'save',
    });

    const handleDateSimulate = (e) => {
        e.preventDefault();
        router.get(`/forecast/${goal.id}`, { as_of: simDate }, { preserveState: true });
    };

    const handleTargetChange = (month, val) => {
        const valNum = num(val);
        setData((prev) => ({
            ...prev,
            targets: { ...prev.targets, [month]: valNum },
            fixed: { ...prev.fixed, [month]: true }, // Mark as fixed when edited
        }));
    };

    const handleFixedChange = (month, checked) => {
        setData((prev) => ({
            ...prev,
            fixed: { ...prev.fixed, [month]: checked },
        }));
    };

    const handleSubmit = (actionType) => (e) => {
        e.preventDefault();
        put(`/forecast/${goal.id}`, {
            data: {
                ...data,
                action: actionType,
                as_of: simDate,
            },
        });
    };

    // Live total calculation
    const totalAllocated = Object.values(data.targets).reduce((acc, v) => acc + num(v), 0);
    const goalTarget = num(goal.target_amount);
    const diff = totalAllocated - goalTarget;

    // Helper for month labels
    const getCellLabel = (phase, target, achieved) => {
        const remaining = Math.max(target - achieved, 0);
        const over = Math.max(achieved - target, 0);
        if (target === 0) return 'No target set';
        if (over > 0) return `Target exceeded by ${formatRs(over)}`;
        if (phase === 'past') return remaining > 0 ? `Missed by ${formatRs(remaining)} / ${formatRs(target)}` : 'Target hit';
        return `Remaining ${formatRs(remaining)} / ${formatRs(target)}`;
    };

    return (
        <Layout title={goal.title}>
            <p className="crumb">
                <Link href="/forecast">&larr; All goals</Link>
            </p>

            <div className="head" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 20, flexWrap: 'wrap', gap: 14 }}>
                <div>
                    <h1 style={{ fontSize: 24, margin: '0 0 4px', color: '#0f172a' }}>{goal.title}</h1>
                    <div className="mute">
                        Rep: <strong>{goal.rep?.name || 'Unassigned'}</strong> &middot; Year {goal.year}
                    </div>
                </div>

                {/* Simulate today's date */}
                <form onSubmit={handleDateSimulate} className="asof" style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                    <label style={{ fontSize: 13, fontWeight: 600, display: 'flex', alignItems: 'center', gap: 6 }}>
                        Simulate today&apos;s date:
                        <input
                            type="date"
                            value={simDate}
                            onChange={(e) => setSimDate(e.target.value)}
                            style={{ padding: '4px 8px', fontSize: 13 }}
                        />
                    </label>
                    <button className="btn ghost" type="submit" style={{ padding: '5px 12px', fontSize: 13 }}>
                        Apply
                    </button>
                </form>
            </div>

            {/* Stats Grid */}
            <div className="grid stats" style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: 14, marginBottom: 20 }}>
                <div className="card stat">
                    <div className="k">Annual goal</div>
                    <div className="v">LKR {formatRs(s.annualTarget)}</div>
                    {s.baseline > 0 && (
                        <div className="s">
                            Last year {formatRs(s.baseline)}
                            {s.growthPct !== null && ` · +${s.growthPct}% growth needed`}
                        </div>
                    )}
                </div>
                <div className="card stat">
                    <div className="k">Achieved so far</div>
                    <div className="v">LKR {formatRs(s.achieved)}</div>
                    <div className="s">
                        {s.annualTarget > 0 ? Math.min(100, Math.floor((s.achieved * 100) / s.annualTarget)) : 0}% of annual goal
                    </div>
                </div>
                <div className="card stat">
                    <div className="k">Still to earn</div>
                    <div className="v">LKR {formatRs(s.remainingAnnual)}</div>
                    <div className="s">{s.monthsLeft} month(s) left including this one</div>
                </div>
                <div className="card stat">
                    <div className="k">Needed per remaining month</div>
                    <div className="v">{s.requiredPerMonth === null ? '-' : `LKR ${formatRs(s.requiredPerMonth)}`}</div>
                    <div className="s">To still reach the annual goal</div>
                </div>
                <div className="card stat">
                    <div className="k">Allocated across months</div>
                    <div className="v">LKR {formatRs(totalAllocated)}</div>
                    <div className="s">Should equal the annual goal</div>
                </div>
            </div>

            {/* Mismatch Alert */}
            {diff !== 0 && (
                <div className="note warn" style={{ marginBottom: 20 }}>
                    {diff > 0
                        ? `Months add up to LKR ${formatRs(diff)} MORE than the annual goal of LKR ${formatRs(goalTarget)}. Use "Save & rebalance other months" to lower the unfixed months, or raise the annual goal.`
                        : `Months add up to LKR ${formatRs(-diff)} LESS than the annual goal of LKR ${formatRs(goalTarget)}. Use "Save & rebalance other months" to raise the unfixed months.`}
                </div>
            )}

            {/* Months Grid */}
            <div className="grid months" style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(260px, 1fr))', gap: 16, marginBottom: 24 }}>
                {(s.cells || []).map((c) => {
                    const currentTarget = data.targets[c.month] !== undefined ? num(data.targets[c.month]) : c.target;
                    const isFixed = !!data.fixed[c.month];
                    const achieved = num(c.achieved);
                    const pct = currentTarget > 0 ? Math.min(100, Math.floor((achieved * 100) / currentTarget)) : achieved > 0 ? 100 : 0;
                    const cellLabel = getCellLabel(c.phase, currentTarget, achieved);
                    const isGood = achieved > currentTarget;
                    const isShort = c.phase === 'past' && currentTarget > achieved;

                    return (
                        <div key={c.month} className={`card cell ${c.phase}`} style={{ padding: 16 }}>
                            <div className="top" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 10 }}>
                                <span className="name" style={{ fontWeight: 700, fontSize: 14 }}>
                                    {c.name} {goal.year}
                                </span>
                                <span className="badge" style={{ fontSize: 11, padding: '2px 8px', borderRadius: 4, background: '#f1f5f9' }}>
                                    {c.phase === 'current'
                                        ? `This month · ${c.daysLeft} day(s) left`
                                        : c.phase === 'past'
                                        ? 'Past'
                                        : 'Upcoming'}
                                </span>
                            </div>

                            <div className="tgt" style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 8 }}>
                                <span className="prefix" style={{ fontSize: 13, color: 'var(--mute)' }}>
                                    Target LKR
                                </span>
                                <input
                                    type="number"
                                    min="0"
                                    step="1"
                                    value={currentTarget}
                                    onChange={(e) => handleTargetChange(c.month, e.target.value)}
                                    style={{ flex: 1, padding: '4px 8px', fontWeight: 600, textAlign: 'right' }}
                                    required
                                />
                            </div>

                            <label
                                className="fixed"
                                style={{ display: 'flex', alignItems: 'center', gap: 6, fontSize: 12, color: 'var(--mute)', marginBottom: 10, cursor: 'pointer' }}
                                title="Fixed months keep their value when you rebalance the others"
                            >
                                <input
                                    type="checkbox"
                                    checked={isFixed}
                                    onChange={(e) => handleFixedChange(c.month, e.target.checked)}
                                />
                                Fixed (set by manager)
                            </label>

                            {/* Progress bar */}
                            <div className="bar" style={{ height: 6, background: '#e2e8f0', borderRadius: 3, overflow: 'hidden', marginBottom: 8 }}>
                                <span
                                    style={{
                                        display: 'block',
                                        height: '100%',
                                        width: `${pct}%`,
                                        background: pct >= 100 ? '#16a34a' : pct >= 50 ? '#2563eb' : '#f59e0b',
                                    }}
                                ></span>
                            </div>

                            <div
                                className={`remaining ${isGood ? 'good' : isShort ? 'short' : ''}`}
                                style={{
                                    fontSize: 12.5,
                                    fontWeight: 600,
                                    color: isGood ? '#15803d' : isShort ? '#b91c1c' : '#334155',
                                    marginBottom: 4,
                                }}
                            >
                                {cellLabel}
                            </div>
                            <div className="mute small" style={{ fontSize: 11.5 }}>
                                Earned: LKR {formatRs(achieved)}
                            </div>
                        </div>
                    );
                })}
            </div>

            {/* Actions */}
            <div className="actions" style={{ display: 'flex', gap: 12, justifyContent: 'flex-end' }}>
                <button
                    className="btn"
                    type="button"
                    onClick={handleSubmit('save')}
                    disabled={processing}
                >
                    Save targets
                </button>
                <button
                    className="btn ghost"
                    type="button"
                    onClick={handleSubmit('rebalance')}
                    title="Months not marked Fixed are re-split so the total equals the annual goal"
                    disabled={processing}
                >
                    Save &amp; rebalance other months
                </button>
            </div>
        </Layout>
    );
}
