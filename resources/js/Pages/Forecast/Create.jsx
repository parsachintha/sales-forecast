import React, { useState, useMemo } from 'react';
import Layout from '@/Components/Layout';
import { Link, useForm } from '@inertiajs/react';
import { MONTHS, formatRs, num } from '@/Utils/math';

export default function ForecastCreate({ reps = [], marketingPlans = [], selectedPlan = null }) {
    const [mode, setMode] = useState('company'); // 'company' | 'single'
    const [selectedRepIds, setSelectedRepIds] = useState(reps.map((r) => r.id));
    const [companyTotalInput, setCompanyTotalInput] = useState(400000);

    // Initial products
    const initialProducts = useMemo(() => {
        if (selectedPlan && Array.isArray(selectedPlan.sales_forecast_monthly) && selectedPlan.sales_forecast_monthly.length > 0) {
            return selectedPlan.sales_forecast_monthly.map((row) => {
                const legacyHalf = row.m7_12 ? num(row.m7_12) / 6 : 0;
                let annual = 0;
                const months = {};
                for (let m = 1; m <= 12; m++) {
                    const val = row[`m${m}`] !== undefined ? num(row[`m${m}`]) : (m >= 7 ? legacyHalf : 0);
                    months[m] = val;
                    annual += val;
                }
                return {
                    name: row.product_service || 'Product',
                    annual_target: annual,
                    months,
                };
            });
        }
        return [
            {
                name: 'Product A',
                annual_target: 160000,
                months: { 1: 13334, 2: 13334, 3: 13334, 4: 13334, 5: 13333, 6: 13333, 7: 13333, 8: 13333, 9: 13333, 10: 13333, 11: 13333, 12: 13333 },
            },
            {
                name: 'Product B',
                annual_target: 240000,
                months: { 1: 20000, 2: 20000, 3: 20000, 4: 20000, 5: 20000, 6: 20000, 7: 20000, 8: 20000, 9: 20000, 10: 20000, 11: 20000, 12: 20000 },
            },
        ];
    }, [selectedPlan]);

    const [products, setProducts] = useState(initialProducts);

    // Forms
    const companyForm = useForm({
        goal_mode: 'company',
        company_title: '2027 Company Annual Sales Goal',
        year: 2027,
        marketing_plan_id: selectedPlan?.id || '',
        rep_ids: reps.map((r) => r.id),
        products: initialProducts,
    });

    const singleForm = useForm({
        goal_mode: 'single',
        rep_id: reps[0]?.id || '',
        title: 'Annual Sales Income Goal',
        year: 2027,
        baseline_amount: 100000,
        target_amount: 360000,
    });

    // Rep Selection toggles
    const toggleRep = (id) => {
        const next = selectedRepIds.includes(id)
            ? selectedRepIds.filter((x) => x !== id)
            : [...selectedRepIds, id];
        setSelectedRepIds(next);
        companyForm.setData('rep_ids', next);
    };

    const toggleAllReps = (select) => {
        const next = select ? reps.map((r) => r.id) : [];
        setSelectedRepIds(next);
        companyForm.setData('rep_ids', next);
    };

    // Plan loader
    const handlePlanChange = (planId) => {
        companyForm.setData('marketing_plan_id', planId);
        const found = marketingPlans.find((p) => String(p.id) === String(planId));
        if (found && Array.isArray(found.sales_forecast_monthly) && found.sales_forecast_monthly.length > 0) {
            const loaded = found.sales_forecast_monthly.map((row) => {
                const legacyHalf = row.m7_12 ? num(row.m7_12) / 6 : 0;
                let annual = 0;
                const months = {};
                for (let m = 1; m <= 12; m++) {
                    const val = row[`m${m}`] !== undefined ? num(row[`m${m}`]) : (m >= 7 ? legacyHalf : 0);
                    months[m] = val;
                    annual += val;
                }
                return {
                    name: row.product_service || 'Product',
                    annual_target: annual,
                    months,
                };
            });
            setProducts(loaded);
            companyForm.setData('products', loaded);
        }
    };

    // Product actions
    const addProduct = () => {
        const next = [
            ...products,
            {
                name: `Product ${String.fromCharCode(65 + products.length)}`,
                annual_target: 120000,
                months: Array.from({ length: 12 }).reduce((acc, _, i) => ({ ...acc, [i + 1]: 10000 }), {}),
            },
        ];
        setProducts(next);
        companyForm.setData('products', next);
    };

    const removeProduct = (idx) => {
        if (products.length <= 1) {
            alert('At least one product is required.');
            return;
        }
        const next = products.filter((_, i) => i !== idx);
        setProducts(next);
        companyForm.setData('products', next);
    };

    const updateProductName = (idx, name) => {
        const next = products.map((p, i) => (i === idx ? { ...p, name } : p));
        setProducts(next);
        companyForm.setData('products', next);
    };

    const updateProductAnnual = (idx, val) => {
        const annual_target = num(val);
        const next = products.map((p, i) => (i === idx ? { ...p, annual_target } : p));
        setProducts(next);
        companyForm.setData('products', next);
    };

    const updateProductMonth = (idx, month, val) => {
        const mVal = num(val);
        const next = products.map((p, i) => {
            if (i === idx) {
                return {
                    ...p,
                    months: { ...p.months, [month]: mVal },
                };
            }
            return p;
        });
        setProducts(next);
        companyForm.setData('products', next);
    };

    const splitProductEvenly = (idx) => {
        const p = products[idx];
        const annual = num(p.annual_target);
        const base = Math.floor(annual / 12);
        const remainder = annual - base * 12;
        const newMonths = {};
        for (let m = 1; m <= 12; m++) {
            newMonths[m] = base + (m <= remainder ? 1 : 0);
        }
        const next = products.map((item, i) => (i === idx ? { ...item, months: newMonths } : item));
        setProducts(next);
        companyForm.setData('products', next);
    };

    const clearProductMonths = (idx) => {
        const newMonths = {};
        for (let m = 1; m <= 12; m++) newMonths[m] = 0;
        const next = products.map((item, i) => (i === idx ? { ...item, months: newMonths } : item));
        setProducts(next);
        companyForm.setData('products', next);
    };

    const divideRemaining = (idx) => {
        const p = products[idx];
        const annual = num(p.annual_target);
        let allocated = 0;
        const zeroMonths = [];
        for (let m = 1; m <= 12; m++) {
            const v = num(p.months?.[m]);
            if (v > 0) allocated += v;
            else zeroMonths.push(m);
        }
        const remaining = annual - allocated;
        if (remaining <= 0) return;

        const targetMonths = zeroMonths.length > 0 ? zeroMonths : Array.from({ length: 12 }, (_, i) => i + 1);
        const base = Math.floor(remaining / targetMonths.length);
        const rem = remaining - base * targetMonths.length;

        const updated = { ...p.months };
        targetMonths.forEach((m, pos) => {
            updated[m] = (zeroMonths.length > 0 ? 0 : num(updated[m])) + base + (pos < rem ? 1 : 0);
        });

        const next = products.map((item, i) => (i === idx ? { ...item, months: updated } : item));
        setProducts(next);
        companyForm.setData('products', next);
    };

    const applyTotalCompanyGoal = () => {
        const total = num(companyTotalInput);
        if (total <= 0) {
            alert('Please enter a valid company annual target greater than 0.');
            return;
        }
        const count = products.length || 1;
        const share = Math.floor(total / count);
        const remainder = total - share * count;

        const next = products.map((p, idx) => {
            const pAnnual = share + (idx < remainder ? 1 : 0);
            const baseMonth = Math.floor(pAnnual / 12);
            const mRem = pAnnual - baseMonth * 12;
            const newMonths = {};
            for (let m = 1; m <= 12; m++) {
                newMonths[m] = baseMonth + (m <= mRem ? 1 : 0);
            }
            return {
                ...p,
                annual_target: pAnnual,
                months: newMonths,
            };
        });
        setProducts(next);
        companyForm.setData('products', next);
    };

    // Totals calculations
    const { totalAnnual, monthlyTotals, avgMonthly, perRepMonthly } = useMemo(() => {
        let tAnnual = 0;
        const mTotals = Array(12).fill(0);
        products.forEach((p) => {
            tAnnual += num(p.annual_target);
            for (let m = 1; m <= 12; m++) {
                mTotals[m - 1] += num(p.months?.[m]);
            }
        });
        const repCount = selectedRepIds.length;
        const avgM = tAnnual / 12;
        const perRep = repCount > 0 ? avgM / repCount : 0;
        return {
            totalAnnual: tAnnual,
            monthlyTotals: mTotals,
            avgMonthly: avgM,
            perRepMonthly: perRep,
        };
    }, [products, selectedRepIds]);

    const handleCompanySubmit = (e) => {
        e.preventDefault();
        companyForm.transform((data) => ({
            ...data,
            products: products.map((p) => ({
                name: p.name,
                annual_target: p.annual_target,
                months: p.months,
            })),
            rep_ids: selectedRepIds,
        }));
        companyForm.post('/forecast');
    };

    const handleSingleSubmit = (e) => {
        e.preventDefault();
        singleForm.post('/forecast');
    };

    return (
        <Layout title="Set Sales Goals">
            <p className="crumb">
                <Link href="/forecast">&larr; Back to all goals</Link>
            </p>

            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-end', marginBottom: 20, flexWrap: 'wrap', gap: 12 }}>
                <div>
                    <h1 style={{ fontSize: 24, margin: '0 0 6px', color: '#0f172a' }}>Set Sales Income Goal</h1>
                    <div style={{ color: 'var(--mute)', fontSize: '14.5px' }}>
                        Establish company goals product-wise and cascade them to sales reps.
                    </div>
                </div>
                <div style={{ display: 'flex', gap: 8, background: '#e2e8f0', padding: 4, borderRadius: 8 }}>
                    <button
                        type="button"
                        className={mode === 'company' ? 'btn' : 'btn ghost'}
                        style={{ padding: '6px 14px', fontSize: 13 }}
                        onClick={() => setMode('company')}
                    >
                        🏢 Company Goal (Cascade to Reps)
                    </button>
                    <button
                        type="button"
                        className={mode === 'single' ? 'btn' : 'btn ghost'}
                        style={{ padding: '6px 14px', fontSize: 13 }}
                        onClick={() => setMode('single')}
                    >
                        👤 Single Rep Goal
                    </button>
                </div>
            </div>

            {/* MODE 1: COMPANY GOAL & PRODUCT-WISE CASCADE */}
            {mode === 'company' && (
                <form onSubmit={handleCompanySubmit}>
                    {/* Section 1: Overview */}
                    <div className="card" style={{ marginBottom: 20 }}>
                        <h3 style={{ margin: '0 0 14px', fontSize: 16, color: '#1e3a8a' }}>
                            1. Company Target Overview &amp; Rep Assignment
                        </h3>
                        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: 16 }}>
                            <div>
                                <label style={{ fontWeight: 600, fontSize: 13, display: 'block', marginBottom: 5 }}>
                                    Goal Title *
                                </label>
                                <input
                                    type="text"
                                    value={companyForm.data.company_title}
                                    onChange={(e) => companyForm.setData('company_title', e.target.value)}
                                    required
                                />
                            </div>
                            <div>
                                <label style={{ fontWeight: 600, fontSize: 13, display: 'block', marginBottom: 5 }}>
                                    Target Year *
                                </label>
                                <input
                                    type="number"
                                    value={companyForm.data.year}
                                    onChange={(e) => companyForm.setData('year', e.target.value)}
                                    min="2020"
                                    max="2100"
                                    required
                                />
                            </div>
                            <div>
                                <label style={{ fontWeight: 700, fontSize: 13, display: 'block', marginBottom: 5, color: '#1e40af' }}>
                                    🏢 Total Company Annual Goal (LKR)
                                </label>
                                <div style={{ display: 'flex', gap: 6 }}>
                                    <input
                                        type="number"
                                        value={companyTotalInput}
                                        onChange={(e) => setCompanyTotalInput(e.target.value)}
                                        min="0"
                                        step="1"
                                        style={{ fontWeight: 700, fontSize: 15, borderColor: '#2563eb', background: '#f0f7ff' }}
                                        placeholder="e.g. 400000"
                                    />
                                    <button
                                        type="button"
                                        className="btn"
                                        style={{ padding: '6px 12px', fontSize: 12, whiteSpace: 'nowrap' }}
                                        onClick={applyTotalCompanyGoal}
                                        title="Distribute this total equally across products"
                                    >
                                        Split Across Products
                                    </button>
                                </div>
                                <span style={{ fontSize: 11, color: 'var(--mute)' }}>
                                    You can enter company target here or set it product-wise below.
                                </span>
                            </div>
                            <div>
                                <label style={{ fontWeight: 600, fontSize: 13, display: 'block', marginBottom: 5 }}>
                                    Import from SEDD Marketing Plan
                                </label>
                                <select
                                    value={companyForm.data.marketing_plan_id}
                                    onChange={(e) => handlePlanChange(e.target.value)}
                                >
                                    <option value="">-- Manual Entry or Select Plan --</option>
                                    {marketingPlans.map((mp) => (
                                        <option key={mp.id} value={mp.id}>
                                            {mp.business_name} ({mp.plan_period_from ? String(mp.plan_period_from).slice(0, 4) : 'Plan'})
                                        </option>
                                    ))}
                                </select>
                            </div>
                        </div>

                        {/* Rep Selection */}
                        <div style={{ marginTop: 16, borderTop: '1px solid #e2e8f0', paddingTop: 14 }}>
                            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 8, flexWrap: 'wrap', gap: 8 }}>
                                <label style={{ fontWeight: 600, fontSize: 13, margin: 0 }}>
                                    Assign / Divide targets to Sales Reps (Optional):
                                    <span style={{ fontWeight: 'normal', color: 'var(--mute)', fontSize: 12, marginLeft: 4 }}>
                                        ({selectedRepIds.length} selected)
                                    </span>
                                </label>
                                <div style={{ display: 'flex', gap: 8 }}>
                                    <button
                                        type="button"
                                        className="btn ghost"
                                        style={{ padding: '2px 8px', fontSize: '11.5px' }}
                                        onClick={() => toggleAllReps(true)}
                                    >
                                        Select All
                                    </button>
                                    <button
                                        type="button"
                                        className="btn ghost"
                                        style={{ padding: '2px 8px', fontSize: '11.5px', color: '#dc2626', borderColor: '#fca5a5' }}
                                        onClick={() => toggleAllReps(false)}
                                    >
                                        Clear All (Company Goal Only)
                                    </button>
                                </div>
                            </div>
                            <div style={{ display: 'flex', flexWrap: 'wrap', gap: 12 }}>
                                {reps.map((rep) => {
                                    const isChecked = selectedRepIds.includes(rep.id);
                                    return (
                                        <label
                                            key={rep.id}
                                            style={{
                                                display: 'inline-flex',
                                                alignItems: 'center',
                                                gap: 6,
                                                background: '#f8fafc',
                                                border: '1px solid #cbd5e1',
                                                padding: '6px 12px',
                                                borderRadius: 6,
                                                cursor: 'pointer',
                                                fontSize: 13,
                                            }}
                                        >
                                            <input
                                                type="checkbox"
                                                checked={isChecked}
                                                onChange={() => toggleRep(rep.id)}
                                            />
                                            <strong>{rep.name}</strong>
                                        </label>
                                    );
                                })}
                            </div>
                        </div>
                    </div>

                    {/* Section 2: Products Breakdown */}
                    <div className="card" style={{ marginBottom: 20 }}>
                        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 12, flexWrap: 'wrap', gap: 8 }}>
                            <div>
                                <h3 style={{ margin: '0 0 4px', fontSize: 16, color: '#1e3a8a' }}>
                                    2. Product/Service Annual &amp; Monthly Breakdown
                                </h3>
                            </div>
                            <button type="button" className="btn add-row" onClick={addProduct}>
                                + Add Another Product
                            </button>
                        </div>

                        {/* Product Cards */}
                        {products.map((p, pIdx) => {
                            const annual = num(p.annual_target);
                            let allocated = 0;
                            for (let m = 1; m <= 12; m++) allocated += num(p.months?.[m]);
                            const remaining = annual - allocated;

                            return (
                                <div
                                    key={pIdx}
                                    className="card"
                                    style={{
                                        background: '#ffffff',
                                        border: '1px solid #cbd5e1',
                                        padding: 18,
                                        marginBottom: 16,
                                    }}
                                >
                                    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 12, flexWrap: 'wrap', gap: 10 }}>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: 10, flex: 1, minWidth: 280 }}>
                                            <span style={{ background: '#2563eb', color: '#fff', fontSize: 12, fontWeight: 700, padding: '2px 8px', borderRadius: 4 }}>
                                                #{pIdx + 1}
                                            </span>
                                            <input
                                                type="text"
                                                value={p.name}
                                                placeholder="Product/Service Name"
                                                style={{ fontWeight: 700, fontSize: 15, maxWidth: 320 }}
                                                onChange={(e) => updateProductName(pIdx, e.target.value)}
                                                required
                                            />
                                        </div>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: 10, flexWrap: 'wrap' }}>
                                            <div style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
                                                <label style={{ fontSize: 13, fontWeight: 600, whiteSpace: 'nowrap' }}>
                                                    Annual Goal (LKR):
                                                </label>
                                                <input
                                                    type="number"
                                                    value={annual}
                                                    min="0"
                                                    step="1"
                                                    style={{ width: 140, fontWeight: 700, textAlign: 'right' }}
                                                    onChange={(e) => updateProductAnnual(pIdx, e.target.value)}
                                                    required
                                                />
                                            </div>
                                            <button
                                                type="button"
                                                className="btn ghost"
                                                style={{ padding: '4px 8px', fontSize: 12 }}
                                                onClick={() => splitProductEvenly(pIdx)}
                                                title="Auto-divide annual goal into 12 equal months"
                                            >
                                                ➗ Split 12 Equal
                                            </button>
                                            <button
                                                type="button"
                                                className="btn ghost"
                                                style={{ padding: '4px 8px', fontSize: 12, color: '#dc2626', borderColor: '#fca5a5' }}
                                                onClick={() => clearProductMonths(pIdx)}
                                                title="Clear all monthly values to type your own"
                                            >
                                                ✖ Clear Months
                                            </button>
                                            <button
                                                type="button"
                                                className="btn danger"
                                                style={{ padding: '4px 8px' }}
                                                onClick={() => removeProduct(pIdx)}
                                                title="Delete this product"
                                            >
                                                &times;
                                            </button>
                                        </div>
                                    </div>

                                    {/* Remaining Notification Badge */}
                                    <div
                                        style={{
                                            display: 'flex',
                                            justifyContent: 'space-between',
                                            alignItems: 'center',
                                            marginBottom: 10,
                                            padding: '8px 14px',
                                            borderRadius: 6,
                                            fontSize: 13,
                                            fontWeight: 600,
                                            border: '1px solid',
                                            color: remaining === 0 ? '#15803d' : remaining > 0 ? '#b45309' : '#b91c1c',
                                            backgroundColor: remaining === 0 ? '#f0fdf4' : remaining > 0 ? '#fffbeb' : '#fef2f2',
                                            borderColor: remaining === 0 ? '#bbf7d0' : remaining > 0 ? '#fde68a' : '#fecaca',
                                        }}
                                    >
                                        {remaining === 0 && <span>✅ Fully Allocated ({formatRs(annual)} LKR)</span>}
                                        {remaining > 0 && (
                                            <span>
                                                ⚠️ Remaining to divide: <strong>{formatRs(remaining)} LKR</strong>
                                            </span>
                                        )}
                                        {remaining < 0 && (
                                            <span>
                                                ❌ Over-allocated by <strong>{formatRs(Math.abs(remaining))} LKR</strong>
                                            </span>
                                        )}
                                        {remaining > 0 && (
                                            <button
                                                type="button"
                                                className="btn"
                                                style={{ background: '#d97706', borderColor: '#b45309', fontSize: 12, padding: '4px 10px', color: '#fff' }}
                                                onClick={() => divideRemaining(pIdx)}
                                            >
                                                Divide remaining {formatRs(remaining)} LKR across unfilled months
                                            </button>
                                        )}
                                    </div>

                                    {/* Month 1 to 12 Inputs */}
                                    <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(88px, 1fr))', gap: 6 }}>
                                        {MONTHS.map((mName, mIdx) => {
                                            const mNum = mIdx + 1;
                                            return (
                                                <div
                                                    key={mNum}
                                                    style={{
                                                        background: '#f8fafc',
                                                        border: '1px solid #e2e8f0',
                                                        borderRadius: 6,
                                                        padding: '6px 4px',
                                                        textAlign: 'center',
                                                    }}
                                                >
                                                    <span style={{ fontSize: 12, fontWeight: 700, color: '#334155', display: 'block', marginBottom: 2 }}>
                                                        {mName}
                                                    </span>
                                                    <input
                                                        type="number"
                                                        step="1"
                                                        min="0"
                                                        value={p.months?.[mNum] || 0}
                                                        onChange={(e) => updateProductMonth(pIdx, mNum, e.target.value)}
                                                        style={{ padding: '4px 4px', fontSize: '12.5px', textAlign: 'right', fontWeight: 600 }}
                                                    />
                                                </div>
                                            );
                                        })}
                                    </div>
                                </div>
                            );
                        })}

                        {/* Overall Summary Banner */}
                        <div style={{ background: '#eff6ff', border: '1px solid #bfdbfe', borderRadius: 8, padding: 16, marginTop: 20 }}>
                            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: 14 }}>
                                <div>
                                    <div style={{ fontSize: 12, textTransform: 'uppercase', fontWeight: 700, color: '#1e40af' }}>
                                        Total Company Annual Goal
                                    </div>
                                    <div style={{ fontSize: 24, fontWeight: 800, color: '#1e3a8a' }}>
                                        Rs. {formatRs(totalAnnual)}
                                    </div>
                                </div>
                                <div>
                                    <div style={{ fontSize: 12, textTransform: 'uppercase', fontWeight: 700, color: '#1e40af' }}>
                                        Monthly Company Target
                                    </div>
                                    <div style={{ fontSize: 20, fontWeight: 700, color: '#1e3a8a' }}>
                                        Rs. {formatRs(avgMonthly)} / mo
                                    </div>
                                </div>
                                <div>
                                    <div style={{ fontSize: 12, textTransform: 'uppercase', fontWeight: 700, color: '#047857' }}>
                                        Per Sales Rep (Across {selectedRepIds.length} Reps)
                                    </div>
                                    <div style={{ fontSize: 20, fontWeight: 700, color: '#047857' }}>
                                        Rs. {formatRs(perRepMonthly)} / rep / mo
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Section 3: Rep Share Preview */}
                    <div className="card" style={{ marginBottom: 24 }}>
                        <h3 style={{ margin: '0 0 10px', fontSize: 16, color: '#1e3a8a' }}>
                            3. Preview of Rep Monthly Targets (Cascaded)
                        </h3>
                        <p style={{ fontSize: 13, color: 'var(--mute)', margin: '0 0 12px' }}>
                            This table shows the exact monthly target that will be assigned to each sales rep.
                        </p>
                        <div style={{ overflowX: 'auto' }}>
                            <table className="sedd-table">
                                <thead>
                                    <tr>
                                        <th>Sales Rep</th>
                                        {MONTHS.map((m) => (
                                            <th key={m} className="num">{m}</th>
                                        ))}
                                        <th className="num" style={{ background: '#e0e7ff', fontWeight: 700 }}>Annual Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {selectedRepIds.length === 0 ? (
                                        <tr>
                                            <td colSpan={14} style={{ textAlign: 'center', color: '#475569', padding: 20, background: '#f8fafc' }}>
                                                ℹ️ <strong>No sales reps selected.</strong> The goal will be saved as a <strong>Company Master Goal</strong> with product &amp; monthly breakdown, without assigning quotas to reps.
                                            </td>
                                        </tr>
                                    ) : (
                                        reps
                                            .filter((r) => selectedRepIds.includes(r.id))
                                            .map((rep) => {
                                                const repCount = selectedRepIds.length;
                                                let repAnnual = 0;
                                                const shares = monthlyTotals.map((mVal) => {
                                                    const s = Math.round(mVal / repCount);
                                                    repAnnual += s;
                                                    return s;
                                                });
                                                return (
                                                    <tr key={rep.id}>
                                                        <td><strong>{rep.name}</strong></td>
                                                        {shares.map((s, idx) => (
                                                            <td key={idx} className="num">{formatRs(s)}</td>
                                                        ))}
                                                        <td className="num" style={{ background: '#e0e7ff', fontWeight: 700 }}>
                                                            {formatRs(repAnnual)}
                                                        </td>
                                                    </tr>
                                                );
                                            })
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 12 }}>
                        <Link href="/forecast" className="btn ghost">
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            className="btn success"
                            style={{
                                padding: '10px 24px',
                                fontSize: 15,
                                background: selectedRepIds.length === 0 ? '#2563eb' : '#16a34a',
                                borderColor: selectedRepIds.length === 0 ? '#1d4ed8' : '#15803d',
                            }}
                            disabled={companyForm.processing}
                        >
                            {selectedRepIds.length === 0
                                ? '💾 Save Company Master Goal (No Reps)'
                                : '🚀 Create & Cascade Goals to Reps'}
                        </button>
                    </div>
                </form>
            )}

            {/* MODE 2: SINGLE REP GOAL */}
            {mode === 'single' && (
                <form onSubmit={handleSingleSubmit} className="form card">
                    <label>
                        Sales Rep
                        <select
                            value={singleForm.data.rep_id}
                            onChange={(e) => singleForm.setData('rep_id', e.target.value)}
                            required
                        >
                            <option value="">Choose a rep...</option>
                            {reps.map((rep) => (
                                <option key={rep.id} value={rep.id}>
                                    {rep.name}
                                </option>
                            ))}
                        </select>
                    </label>

                    <label>
                        Goal Title
                        <input
                            type="text"
                            value={singleForm.data.title}
                            onChange={(e) => singleForm.setData('title', e.target.value)}
                            required
                        />
                    </label>

                    <label>
                        Goal Year
                        <input
                            type="number"
                            value={singleForm.data.year}
                            onChange={(e) => singleForm.setData('year', e.target.value)}
                            min="2020"
                            max="2100"
                            required
                        />
                    </label>

                    <label>
                        Last Year&apos;s Income (LKR)
                        <input
                            type="number"
                            value={singleForm.data.baseline_amount}
                            onChange={(e) => singleForm.setData('baseline_amount', e.target.value)}
                            min="0"
                        />
                        <span className="hint">For context only - shows historical benchmark.</span>
                    </label>

                    <label>
                        Annual Target (LKR)
                        <input
                            type="number"
                            value={singleForm.data.target_amount}
                            onChange={(e) => singleForm.setData('target_amount', e.target.value)}
                            min="1"
                            required
                        />
                        <span className="hint">E.g., 360,000 becomes 30,000 per month.</span>
                    </label>

                    <div style={{ marginTop: 12 }}>
                        <button className="btn" type="submit" disabled={singleForm.processing}>
                            Create Single Goal
                        </button>
                    </div>
                </form>
            )}
        </Layout>
    );
}
