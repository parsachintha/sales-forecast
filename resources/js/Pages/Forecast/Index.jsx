import React from 'react';
import Layout from '@/Components/Layout';
import { Link } from '@inertiajs/react';
import { formatRs, num } from '@/Utils/math';

export default function ForecastIndex({ companyGoals = [], goals = [] }) {
    return (
        <Layout title="Sales Forecast & Goals">
            <div className="head" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 20 }}>
                <div>
                    <h1 style={{ fontSize: 24, margin: '0 0 6px', color: '#0f172a' }}>
                        Sales Income Forecast & Rep Allocations
                    </h1>
                    <div className="mute">Company product-wise goals and partitioned sales rep targets.</div>
                </div>
                <Link href="/forecast/create" className="btn">
                    + Set New Goal / Cascade
                </Link>
            </div>

            {/* Company Master Goals Section */}
            {companyGoals && companyGoals.length > 0 && (
                <div style={{ marginTop: 24 }}>
                    <h3 style={{ fontSize: 16, color: '#1e3a8a', margin: '0 0 10px' }}>
                        🏢 Company Product-Wise Master Goals
                    </h3>
                    <div className="card" style={{ padding: 0, overflow: 'hidden' }}>
                        <div style={{ overflowX: 'auto' }}>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Goal Title</th>
                                        <th>Year</th>
                                        <th>Products / Services</th>
                                        <th className="num">Annual Goal (LKR)</th>
                                        <th>Assigned Reps</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {companyGoals.map((cg) => (
                                        <tr key={cg.id}>
                                            <td><strong>{cg.title}</strong></td>
                                            <td>{cg.year}</td>
                                            <td>
                                                {Array.isArray(cg.products) ? (
                                                    cg.products.map((cp, idx) => (
                                                        <span
                                                            key={idx}
                                                            style={{
                                                                display: 'inline-block',
                                                                background: '#eff6ff',
                                                                color: '#1e40af',
                                                                border: '1px solid #bfdbfe',
                                                                fontSize: 12,
                                                                fontWeight: 600,
                                                                padding: '2px 7px',
                                                                borderRadius: 4,
                                                                marginRight: 4,
                                                                marginBottom: 2,
                                                            }}
                                                        >
                                                            {cp.name}: LKR {formatRs(cp.annual_target)}
                                                        </span>
                                                    ))
                                                ) : (
                                                    '-'
                                                )}
                                            </td>
                                            <td className="num" style={{ fontWeight: 700 }}>
                                                {formatRs(cg.total_annual_target)}
                                            </td>
                                            <td>
                                                <span style={{ background: '#f1f5f9', padding: '3px 8px', borderRadius: 4, fontSize: 12, fontWeight: 600 }}>
                                                    {(cg.assigned_rep_ids || []).length} Reps Cascaded
                                                </span>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            )}

            {/* Sales Rep Assigned Targets Section */}
            <div style={{ marginTop: 28 }}>
                <h3 style={{ fontSize: 16, color: '#1e3a8a', margin: '0 0 10px' }}>
                    👤 Assigned Sales Rep Goals (Monthly Tracking)
                </h3>
                <div className="card" style={{ padding: 0, overflow: 'hidden' }}>
                    <div style={{ overflowX: 'auto' }}>
                        <table>
                            <thead>
                                <tr>
                                    <th>Rep</th>
                                    <th>Goal</th>
                                    <th>Year</th>
                                    <th className="num">Annual target (LKR)</th>
                                    <th className="num">Achieved (LKR)</th>
                                    <th className="num">Progress</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                {goals && goals.length > 0 ? (
                                    goals.map((g) => {
                                        const target = num(g.target_amount);
                                        const achieved = num(g.achieved);
                                        const pct = target > 0 ? Math.min(100, Math.floor((achieved * 100) / target)) : 0;
                                        return (
                                            <tr key={g.id}>
                                                <td><strong>{g.rep?.name || 'Unassigned'}</strong></td>
                                                <td>{g.title}</td>
                                                <td>{g.year}</td>
                                                <td className="num">{formatRs(target)}</td>
                                                <td className="num">{formatRs(achieved)}</td>
                                                <td className="num">
                                                    <span style={{ fontWeight: 600, color: pct >= 80 ? '#059669' : pct >= 50 ? '#d97706' : '#dc2626' }}>
                                                        {pct}%
                                                    </span>
                                                </td>
                                                <td className="num">
                                                    <Link href={`/forecast/${g.id}`} className="btn ghost" style={{ padding: '4px 10px', fontSize: 12 }}>
                                                        Open forecast
                                                    </Link>
                                                </td>
                                            </tr>
                                        );
                                    })
                                ) : (
                                    <tr>
                                        <td colSpan="7" className="mute" style={{ textAlign: 'center', padding: 24 }}>
                                            No sales rep goals yet. Click &ldquo;+ Set New Goal / Cascade&rdquo; to create one.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </Layout>
    );
}
