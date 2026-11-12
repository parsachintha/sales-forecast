import React from 'react';
import Layout from '@/Components/Layout';
import { Link } from '@inertiajs/react';
import { formatRs, num } from '@/Utils/math';

export default function MarketingShow({ plan, auth }) {
    const isCompany = auth?.user?.role === 'company';
    const isOfficer = auth?.user?.role === 'officer';

    // Expense totals
    const expenseTotals = (plan.marketing_expense_budget || []).reduce(
        (acc, item) => ({
            ex: acc.ex + num(item.existing_year),
            p1: acc.p1 + num(item.proposed_year1),
            p2: acc.p2 + num(item.proposed_year2),
            p3: acc.p3 + num(item.proposed_year3),
        }),
        { ex: 0, p1: 0, p2: 0, p3: 0 }
    );

    // Monthly 9.1 calculation
    const monthlyCols = Array(12).fill(0);
    let monthlyGrandTotal = 0;
    const monthlyRows = (plan.sales_forecast_monthly || []).map((row) => {
        const legacyHalf = row.m7_12 ? num(row.m7_12) / 6 : 0;
        let rowSum = 0;
        const months = [];
        for (let m = 1; m <= 12; m++) {
            const val = row[`m${m}`] !== undefined ? num(row[`m${m}`]) : (m >= 7 ? legacyHalf : 0);
            rowSum += val;
            monthlyCols[m - 1] += val;
            months.push(val);
        }
        monthlyGrandTotal += rowSum;
        return { product: row.product_service || '-', months, rowSum };
    });

    // 9.2 Long-term calculation
    const longtermTotals = (plan.sales_forecast_longterm || []).reduce(
        (acc, item) => ({
            base: acc.base + num(item.base_year),
            p1: acc.p1 + num(item.proposed_year1),
            p2: acc.p2 + num(item.proposed_year2),
            p3: acc.p3 + num(item.proposed_year3),
        }),
        { base: 0, p1: 0, p2: 0, p3: 0 }
    );

    return (
        <Layout title={`SEDD - ${plan.business_name || 'Marketing Plan'}`}>
            <div className="no-print" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 20 }}>
                <Link href="/marketing-plan" style={{ color: 'var(--mute)', textDecoration: 'none', fontWeight: 600 }}>
                    &larr; Back to all plans
                </Link>
                <div style={{ display: 'flex', gap: 10, flexWrap: 'wrap' }}>
                    {isCompany && (
                        <Link href={`/forecast/create?plan_id=${plan.id}`} className="btn" style={{ background: '#059669', borderColor: '#047857' }}>
                            🎯 Allocate & Cascade Goals to Reps
                        </Link>
                    )}
                    {isOfficer && (
                        <Link href={`/marketing-plan/${plan.id}/edit`} className="btn">
                            ✏️ Edit Plan (Officer)
                        </Link>
                    )}
                    <button type="button" onClick={() => window.print()} className="btn ghost">
                        🖨️ Print / Save as PDF
                    </button>
                </div>
            </div>

            <div className="card" style={{ padding: '36px 42px', border: '1px solid #cbd5e1', boxShadow: '0 4px 6px -1px rgba(0,0,0,0.05)' }}>
                {/* Header Block */}
                <div style={{ textAlign: 'center', borderBottom: '3px double #1a56db', paddingBottom: 20, marginBottom: 26 }}>
                    <div style={{ fontSize: 13, fontWeight: 700, textTransform: 'uppercase', letterSpacing: 1.2, color: '#475569' }}>
                        Government of Sri Lanka
                    </div>
                    <h1 style={{ fontSize: 24, color: '#1e3a8a', margin: '6px 0 2px' }}>
                        Small Enterprise Development Division (SEDD)
                    </h1>
                    <h2 style={{ fontSize: 18, color: '#1d4ed8', fontWeight: 600, margin: '0 0 10px' }}>
                        MSME Practical Marketing Plan
                    </h2>
                    <div style={{ fontSize: 13, color: '#64748b', fontStyle: 'italic' }}>
                        12-Month Operational & Strategic Marketing Document
                    </div>
                </div>

                {/* Entrepreneur Profile */}
                <div style={{ marginBottom: 30, overflowX: 'auto' }}>
                    <table className="sedd-table" style={{ marginTop: 0 }}>
                        <tbody>
                            <tr>
                                <th style={{ width: '25%', background: '#f8fafc' }}>Entrepreneur Name</th>
                                <td style={{ width: '25%' }}><strong>{plan.entrepreneur_name || '-'}</strong></td>
                                <th style={{ width: '25%', background: '#f8fafc' }}>Address / Pre.Lt. (DS) Division</th>
                                <td style={{ width: '25%' }}>{plan.address_division || '-'}</td>
                            </tr>
                            <tr>
                                <th>Telephone / Mobile</th>
                                <td>{plan.telephone || '-'}</td>
                                <th>E-mail</th>
                                <td>{plan.email || '-'}</td>
                            </tr>
                            <tr>
                                <th>Business Name</th>
                                <td><strong>{plan.business_name || '-'}</strong></td>
                                <th>Registered Number</th>
                                <td>{plan.registered_number || '-'}</td>
                            </tr>
                            <tr>
                                <th>Main Production & Service</th>
                                <td colSpan="3">{plan.main_product_service_summary || '-'}</td>
                            </tr>
                            <tr>
                                <th>Plan Period (12 Months)</th>
                                <td colSpan="3">
                                    <strong>From:</strong> {plan.plan_period_from ? String(plan.plan_period_from).slice(0, 10) : '20...../..../....'}
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <strong>To:</strong> {plan.plan_period_to ? String(plan.plan_period_to).slice(0, 10) : '20...../..../....'}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {/* 1. Purpose */}
                <div style={{ marginBottom: 30 }}>
                    <h3 style={{ fontSize: 16, color: '#1e3a8a', borderBottom: '1px solid #cbd5e1', paddingBottom: 6, marginBottom: 12 }}>
                        1. Purpose & Executive Summary of the Marketing Plan
                    </h3>
                    <div style={{ whiteSpace: 'pre-line', color: '#334155', lineHeight: 1.6, padding: '4px 6px' }}>
                        {plan.purpose_executive_summary || 'No summary provided.'}
                    </div>
                </div>

                {/* 2. Products */}
                <div style={{ marginBottom: 30 }}>
                    <h3 style={{ fontSize: 16, color: '#1e3a8a', borderBottom: '1px solid #cbd5e1', paddingBottom: 6, marginBottom: 12 }}>
                        2. Product/Service Description
                    </h3>
                    <div style={{ marginBottom: 14 }}>
                        <strong style={{ color: '#0f172a', fontSize: 14 }}>2.1 Main product/service:</strong>
                        <div style={{ whiteSpace: 'pre-line', color: '#334155', marginTop: 4, padding: '0 6px' }}>
                            {plan.main_products_services || 'None recorded.'}
                        </div>
                    </div>
                    <div>
                        <strong style={{ color: '#0f172a', fontSize: 14 }}>2.2 Proposed future products/services:</strong>
                        <div style={{ whiteSpace: 'pre-line', color: '#334155', marginTop: 4, padding: '0 6px' }}>
                            {plan.future_products_services || 'None recorded.'}
                        </div>
                    </div>
                </div>

                {/* 3. Target Market */}
                <div style={{ marginBottom: 30 }}>
                    <h3 style={{ fontSize: 16, color: '#1e3a8a', borderBottom: '1px solid #cbd5e1', paddingBottom: 6, marginBottom: 12 }}>
                        3. Target Market & Customers Analysis
                    </h3>
                    <div style={{ marginBottom: 14 }}>
                        <strong style={{ color: '#0f172a', fontSize: 14 }}>3.1 Target market areas:</strong>
                        <div style={{ margin: '6px 0 0 16px' }}>
                            <div><strong>• Areas currently covered:</strong> {plan.market_areas_current || 'None'}</div>
                            <div style={{ marginTop: 4 }}><strong>• Areas expected to be covered in the future:</strong> {plan.market_areas_future || 'None'}</div>
                        </div>
                    </div>
                    <div>
                        <strong style={{ color: '#0f172a', fontSize: 14 }}>3.2 Target Customer Profile:</strong>
                        <div style={{ overflowX: 'auto' }}>
                            <table className="sedd-table" style={{ marginTop: 8 }}>
                                <thead>
                                    <tr>
                                        <th style={{ width: '25%' }}>Product / Service</th>
                                        <th style={{ width: '30%' }}>Targeted Area</th>
                                        <th style={{ width: '45%' }}>Goals / Customers (age, income, gender, institutions)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {(plan.target_customer_profiles || []).length > 0 ? (
                                        plan.target_customer_profiles.map((tcp, i) => (
                                            <tr key={i}>
                                                <td>{tcp.product_service || '-'}</td>
                                                <td>{tcp.targeted_area || '-'}</td>
                                                <td>{tcp.goals_customers || '-'}</td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr><td colSpan="3" style={{ textAlign: 'center', color: '#94a3b8' }}>No customer profiles specified.</td></tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {/* 4. Competitor & Supply */}
                <div style={{ marginBottom: 30 }}>
                    <h3 style={{ fontSize: 16, color: '#1e3a8a', borderBottom: '1px solid #cbd5e1', paddingBottom: 6, marginBottom: 12 }}>
                        4. Market and Competitor Analysis
                    </h3>
                    <div style={{ marginBottom: 18 }}>
                        <strong style={{ color: '#0f172a', fontSize: 14 }}>4.1 Identifying the main competitors in the area:</strong>
                        <div style={{ overflowX: 'auto' }}>
                            <table className="sedd-table" style={{ marginTop: 8 }}>
                                <thead>
                                    <tr>
                                        <th style={{ width: '25%' }}>Competitor&apos;s Name</th>
                                        <th style={{ width: '25%' }}>Active Area</th>
                                        <th style={{ width: '25%' }}>Key Strength</th>
                                        <th style={{ width: '25%' }}>Weaknesses / Opportunities we have</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {(plan.competitors || []).length > 0 ? (
                                        plan.competitors.map((c, i) => (
                                            <tr key={i}>
                                                <td><strong>{c.name || '-'}</strong></td>
                                                <td>{c.active_area || '-'}</td>
                                                <td>{c.key_strengths || '-'}</td>
                                                <td>{c.weaknesses_opportunities || '-'}</td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr><td colSpan="4" style={{ textAlign: 'center', color: '#94a3b8' }}>No competitors identified.</td></tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div>
                        <strong style={{ color: '#0f172a', fontSize: 14 }}>4.2 Proposed Supply Capacity of the business:</strong>
                        <div style={{ overflowX: 'auto' }}>
                            <table className="sedd-table" style={{ marginTop: 8 }}>
                                <thead>
                                    <tr>
                                        <th style={{ width: '30%' }}>Product / Service</th>
                                        <th style={{ width: '20%' }}>In Demand Nature</th>
                                        <th style={{ width: '25%' }}>Monthly Production Capacity</th>
                                        <th style={{ width: '25%' }}>Annual Production Capacity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {(plan.supply_capacities || []).length > 0 ? (
                                        plan.supply_capacities.map((sc, i) => (
                                            <tr key={i}>
                                                <td><strong>{sc.product_service || '-'}</strong></td>
                                                <td>{sc.demand_nature || '-'}</td>
                                                <td>{sc.monthly_capacity || '-'}</td>
                                                <td>{sc.annual_capacity || '-'}</td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr><td colSpan="4" style={{ textAlign: 'center', color: '#94a3b8' }}>No supply capacities recorded.</td></tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {/* 5. 7 Ps */}
                <div style={{ marginBottom: 30 }}>
                    <h3 style={{ fontSize: 16, color: '#1e3a8a', borderBottom: '1px solid #cbd5e1', paddingBottom: 6, marginBottom: 12 }}>
                        5. Competitive Analysis (7 Ps Comparison)
                    </h3>
                    <div style={{ overflowX: 'auto' }}>
                        <table className="sedd-table">
                            <thead>
                                <tr>
                                    <th style={{ width: '22%' }}>7 Ps Factors</th>
                                    <th style={{ width: '26%', background: '#eff6ff' }}>Our Business</th>
                                    <th style={{ width: '26%' }}>Competitor 01</th>
                                    <th style={{ width: '26%' }}>Competitor 02</th>
                                </tr>
                            </thead>
                            <tbody>
                                {(plan.competitive_7ps || []).map((p, i) => (
                                    <tr key={i}>
                                        <td><strong>{p.factor}</strong></td>
                                        <td style={{ background: '#f8faff' }}>{p.our_business || '-'}</td>
                                        <td>{p.competitor_01 || '-'}</td>
                                        <td>{p.competitor_02 || '-'}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>

                {/* 6. SWOT */}
                <div style={{ marginBottom: 30 }}>
                    <h3 style={{ fontSize: 16, color: '#1e3a8a', borderBottom: '1px solid #cbd5e1', paddingBottom: 6, marginBottom: 12 }}>
                        6. SWOT Analysis
                    </h3>
                    <div style={{ overflowX: 'auto' }}>
                        <table className="sedd-table" style={{ tableLayout: 'fixed' }}>
                            <thead>
                                <tr>
                                    <th style={{ width: '50%', background: '#ecfdf5', color: '#065f46' }}>Strengths (Internal)</th>
                                    <th style={{ width: '50%', background: '#fef2f2', color: '#991b1b' }}>Weaknesses (Internal)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style={{ verticalAlign: 'top' }}>
                                    <td style={{ whiteSpace: 'pre-line', padding: 12 }}>{plan.swot_strengths || 'None specified'}</td>
                                    <td style={{ whiteSpace: 'pre-line', padding: 12 }}>{plan.swot_weaknesses || 'None specified'}</td>
                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th style={{ background: '#eff6ff', color: '#1e40af' }}>Opportunities (External)</th>
                                    <th style={{ background: '#fffbeb', color: '#92400e' }}>Threats (External)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style={{ verticalAlign: 'top' }}>
                                    <td style={{ whiteSpace: 'pre-line', padding: 12 }}>{plan.swot_opportunities || 'None specified'}</td>
                                    <td style={{ whiteSpace: 'pre-line', padding: 12 }}>{plan.swot_threats || 'None specified'}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {/* 7. 4 Ps */}
                <div style={{ marginBottom: 30 }}>
                    <h3 style={{ fontSize: 16, color: '#1e3a8a', borderBottom: '1px solid #cbd5e1', paddingBottom: 6, marginBottom: 12 }}>
                        7. Suggested Marketing Strategies (4 Ps)
                    </h3>
                    <div style={{ overflowX: 'auto' }}>
                        <table className="sedd-table">
                            <thead>
                                <tr>
                                    <th style={{ width: '25%' }}>Goods and Services (Product)</th>
                                    <th style={{ width: '25%' }}>Price</th>
                                    <th style={{ width: '25%' }}>Place</th>
                                    <th style={{ width: '25%' }}>Promotion</th>
                                </tr>
                            </thead>
                            <tbody>
                                {(plan.strategies_4ps || []).length > 0 ? (
                                    plan.strategies_4ps.map((s, i) => (
                                        <tr key={i}>
                                            <td style={{ whiteSpace: 'pre-line' }}>{s.product || '-'}</td>
                                            <td style={{ whiteSpace: 'pre-line' }}>{s.price || '-'}</td>
                                            <td style={{ whiteSpace: 'pre-line' }}>{s.place || '-'}</td>
                                            <td style={{ whiteSpace: 'pre-line' }}>{s.promotion || '-'}</td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr><td colSpan="4" style={{ textAlign: 'center', color: '#94a3b8' }}>No strategies recorded.</td></tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>

                {/* 8. Expense Budget */}
                <div style={{ marginBottom: 30 }}>
                    <h3 style={{ fontSize: 16, color: '#1e3a8a', borderBottom: '1px solid #cbd5e1', paddingBottom: 6, marginBottom: 12 }}>
                        8. Marketing Expense Budget
                    </h3>
                    <div style={{ overflowX: 'auto' }}>
                        <table className="sedd-table">
                            <thead>
                                <tr>
                                    <th style={{ width: '36%' }}>Expenses Item</th>
                                    <th className="num" style={{ width: '16%' }}>Existing Year (Rs.)</th>
                                    <th className="num" style={{ width: '16%' }}>Proposed 1 Year (Rs.)</th>
                                    <th className="num" style={{ width: '16%' }}>Proposed 2 Years (Rs.)</th>
                                    <th className="num" style={{ width: '16%' }}>Proposed 3 Years (Rs.)</th>
                                </tr>
                            </thead>
                            <tbody>
                                {(plan.marketing_expense_budget || []).map((b, i) => (
                                    <tr key={i}>
                                        <td>{b.item || '-'}</td>
                                        <td className="num">{formatRs(b.existing_year)}</td>
                                        <td className="num">{formatRs(b.proposed_year1)}</td>
                                        <td className="num">{formatRs(b.proposed_year2)}</td>
                                        <td className="num">{formatRs(b.proposed_year3)}</td>
                                    </tr>
                                ))}
                                <tr className="total-row">
                                    <td>Collection (Total)</td>
                                    <td className="num">{formatRs(expenseTotals.ex)}</td>
                                    <td className="num">{formatRs(expenseTotals.p1)}</td>
                                    <td className="num">{formatRs(expenseTotals.p2)}</td>
                                    <td className="num">{formatRs(expenseTotals.p3)}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {/* 9. Sales Forecast */}
                <div style={{ marginBottom: 30 }}>
                    <h3 style={{ fontSize: 16, color: '#1e3a8a', borderBottom: '1px solid #cbd5e1', paddingBottom: 6, marginBottom: 12 }}>
                        9. Sales Income Forecast
                    </h3>

                    {/* 9.1 Monthly */}
                    <div style={{ marginBottom: 22 }}>
                        <strong style={{ color: '#0f172a', fontSize: 14 }}>
                            9.1 Estimated monthly sales revenue for the proposed 1st year (Rs.):
                        </strong>
                        <div style={{ overflowX: 'auto' }}>
                            <table className="sedd-table" style={{ marginTop: 8, minWidth: 1100 }}>
                                <thead>
                                    <tr>
                                        <th style={{ minWidth: 150 }}>Product / Service</th>
                                        {[...Array(12)].map((_, i) => (
                                            <th key={i} className="num" style={{ minWidth: 65 }}>{i + 1} M</th>
                                        ))}
                                        <th className="num" style={{ background: '#e0e7ff', minWidth: 110 }}>1 Year Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {monthlyRows.map((r, i) => (
                                        <tr key={i}>
                                            <td><strong>{r.product}</strong></td>
                                            {r.months.map((mVal, mi) => (
                                                <td key={mi} className="num">{formatRs(mVal)}</td>
                                            ))}
                                            <td className="num" style={{ fontWeight: 700, background: '#f5f7ff' }}>
                                                {formatRs(r.rowSum)}
                                            </td>
                                        </tr>
                                    ))}
                                    <tr className="total-row">
                                        <td>Total Income (Rs.)</td>
                                        {monthlyCols.map((cVal, ci) => (
                                            <td key={ci} className="num">{formatRs(cVal)}</td>
                                        ))}
                                        <td className="num" style={{ background: '#e0e7ff' }}>
                                            {formatRs(monthlyGrandTotal)}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {/* 9.2 Long-term */}
                    <div>
                        <strong style={{ color: '#0f172a', fontSize: 14 }}>
                            9.2 Long-term (3-year) sales revenue estimate:
                        </strong>
                        <div style={{ overflowX: 'auto' }}>
                            <table className="sedd-table" style={{ marginTop: 8 }}>
                                <thead>
                                    <tr>
                                        <th style={{ width: '36%' }}>Product / Service</th>
                                        <th className="num" style={{ width: '16%' }}>Foundations Year (Rs.)</th>
                                        <th className="num" style={{ width: '16%' }}>Proposed 1 Year (Rs.)</th>
                                        <th className="num" style={{ width: '16%' }}>Proposed 2 Years (Rs.)</th>
                                        <th className="num" style={{ width: '16%' }}>Proposed 3 Years (Rs.)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {(plan.sales_forecast_longterm || []).map((lt, i) => (
                                        <tr key={i}>
                                            <td><strong>{lt.product_service || '-'}</strong></td>
                                            <td className="num">{formatRs(lt.base_year)}</td>
                                            <td className="num">{formatRs(lt.proposed_year1)}</td>
                                            <td className="num">{formatRs(lt.proposed_year2)}</td>
                                            <td className="num">{formatRs(lt.proposed_year3)}</td>
                                        </tr>
                                    ))}
                                    <tr className="total-row">
                                        <td>Collection (Total Sales)</td>
                                        <td className="num">{formatRs(longtermTotals.base)}</td>
                                        <td className="num">{formatRs(longtermTotals.p1)}</td>
                                        <td className="num">{formatRs(longtermTotals.p2)}</td>
                                        <td className="num">{formatRs(longtermTotals.p3)}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {/* 10. Action Plan */}
                <div style={{ marginBottom: 20 }}>
                    <h3 style={{ fontSize: 16, color: '#1e3a8a', borderBottom: '1px solid #cbd5e1', paddingBottom: 6, marginBottom: 12 }}>
                        10. Action Plan & Monitoring
                    </h3>
                    <div style={{ overflowX: 'auto' }}>
                        <table className="sedd-table">
                            <thead>
                                <tr>
                                    <th style={{ width: '40%' }}>Task / Activity</th>
                                    <th style={{ width: '25%' }}>Responsible Person</th>
                                    <th style={{ width: '20%' }}>Time Frame</th>
                                    <th style={{ width: '15%' }}>Progress / Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                {(plan.action_plans || []).length > 0 ? (
                                    plan.action_plans.map((act, i) => (
                                        <tr key={i}>
                                            <td>{act.task || '-'}</td>
                                            <td>{act.responsible_person || '-'}</td>
                                            <td>{act.time_frame || '-'}</td>
                                            <td>
                                                <span style={{
                                                    display: 'inline-block',
                                                    padding: '2px 8px',
                                                    borderRadius: 4,
                                                    fontSize: 12,
                                                    fontWeight: 600,
                                                    background: '#e0f2fe',
                                                    color: '#0369a1'
                                                }}>
                                                    {act.status || 'Pending'}
                                                </span>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr><td colSpan="4" style={{ textAlign: 'center', color: '#94a3b8' }}>No action plans recorded.</td></tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>

                {/* Signature Sign-offs */}
                <div style={{ marginTop: 50, paddingTop: 30, borderTop: '1px dashed #cbd5e1', display: 'flex', justifyContent: 'space-between' }}>
                    <div style={{ textAlign: 'center', width: 220 }}>
                        <div style={{ borderBottom: '1px solid #94a3b8', height: 40, marginBottom: 6 }}></div>
                        <div style={{ fontSize: 12, fontWeight: 600 }}>Entrepreneur&apos;s Signature</div>
                        <div style={{ fontSize: 11, color: '#64748b' }}>Date: ........................</div>
                    </div>
                    <div style={{ textAlign: 'center', width: 220 }}>
                        <div style={{ borderBottom: '1px solid #94a3b8', height: 40, marginBottom: 6 }}></div>
                        <div style={{ fontSize: 12, fontWeight: 600 }}>SEDD Enterprise Development Officer</div>
                        <div style={{ fontSize: 11, color: '#64748b' }}>Signature & Official Stamp</div>
                    </div>
                </div>
            </div>
        </Layout>
    );
}
