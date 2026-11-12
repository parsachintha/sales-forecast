import React, { useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import Layout from '@/Components/Layout';
import { formatNum, MONTH_NAMES } from '@/Utils/math';

export default function MarketingForm({ plan = {}, isEdit = false }) {
    // Helper to extract SWOT list
    const parseSwot = (text) => {
        if (!text) return [''];
        const lines = text.split('\n').map((l) => l.replace(/^[•\s-]+/, '').trim()).filter(Boolean);
        return lines.length > 0 ? lines : [''];
    };

    const { data, setData, post, put, processing, errors } = useForm({
        // Header
        entrepreneur_name: plan.entrepreneur_name || '',
        address_division: plan.address_division || '',
        telephone: plan.telephone || '',
        email: plan.email || '',
        business_name: plan.business_name || '',
        registered_number: plan.registered_number || '',
        main_product_service_summary: plan.main_product_service_summary || '',
        plan_period_from: plan.plan_period_from ? plan.plan_period_from.substring(0, 10) : '',
        plan_period_to: plan.plan_period_to ? plan.plan_period_to.substring(0, 10) : '',

        // 1. Purpose
        purpose_executive_summary: plan.purpose_executive_summary || '',

        // 2. Product Description
        main_products_services: plan.main_products_services || '',
        future_products_services: plan.future_products_services || '',

        // 3. Target Market
        market_areas_current: plan.market_areas_current || '',
        market_areas_future: plan.market_areas_future || '',
        target_customer_profiles: plan.target_customer_profiles?.length
            ? plan.target_customer_profiles
            : [{ product_service: '', targeted_area: '', goals_customers: '' }],

        // 4. Competitors & Supply Capacity
        competitors: plan.competitors?.length
            ? plan.competitors
            : [{ name: '', active_area: '', key_strengths: '', weaknesses_opportunities: '' }],
        supply_capacities: plan.supply_capacities?.length
            ? plan.supply_capacities
            : [{ product_service: '', demand_nature: 'Medium', monthly_capacity: '', annual_capacity: '' }],

        // 5. 7 Ps
        competitive_7ps: plan.competitive_7ps?.length
            ? plan.competitive_7ps
            : [
                  { factor: '1. Product', our_business: '', competitor_01: '', competitor_02: '' },
                  { factor: '2. Price', our_business: '', competitor_01: '', competitor_02: '' },
                  { factor: '3. Place / Delivery (Place)', our_business: '', competitor_01: '', competitor_02: '' },
                  { factor: '4. Promotion', our_business: '', competitor_01: '', competitor_02: '' },
                  { factor: '5. People', our_business: '', competitor_01: '', competitor_02: '' },
                  { factor: '6. Physical Evidence', our_business: '', competitor_01: '', competitor_02: '' },
                  { factor: '7. Process', our_business: '', competitor_01: '', competitor_02: '' },
              ],

        // 6. SWOT Lists
        swot_strengths_list: parseSwot(plan.swot_strengths),
        swot_weaknesses_list: parseSwot(plan.swot_weaknesses),
        swot_opportunities_list: parseSwot(plan.swot_opportunities),
        swot_threats_list: parseSwot(plan.swot_threats),

        // 7. 4 Ps Strategies
        strategies_4ps: plan.strategies_4ps?.length
            ? plan.strategies_4ps
            : [{ product: '', price: '', place: '', promotion: '' }],

        // 8. Expense Budget
        marketing_expense_budget: plan.marketing_expense_budget?.length
            ? plan.marketing_expense_budget
            : [
                  { item: 'Digital Marketing (Social Media Ads)', existing_year: 0, proposed_year1: 0, proposed_year2: 0, proposed_year3: 0 },
                  { item: 'Liquid and advertising materials (Flyers/Banners)', existing_year: 0, proposed_year1: 0, proposed_year2: 0, proposed_year3: 0 },
                  { item: 'Growth Commissions and discounts', existing_year: 0, proposed_year1: 0, proposed_year2: 0, proposed_year3: 0 },
                  { item: 'Packaging and label costs', existing_year: 0, proposed_year1: 0, proposed_year2: 0, proposed_year3: 0 },
                  { item: 'Delivery and travel expenses', existing_year: 0, proposed_year1: 0, proposed_year2: 0, proposed_year3: 0 },
              ],

        // 9. Sales Forecasts
        sales_forecast_monthly: plan.sales_forecast_monthly?.length
            ? plan.sales_forecast_monthly
            : [
                  { product_service: 'Production A', m1: 0, m2: 0, m3: 0, m4: 0, m5: 0, m6: 0, m7: 0, m8: 0, m9: 0, m10: 0, m11: 0, m12: 0 },
                  { product_service: 'Production B', m1: 0, m2: 0, m3: 0, m4: 0, m5: 0, m6: 0, m7: 0, m8: 0, m9: 0, m10: 0, m11: 0, m12: 0 },
              ],
        sales_forecast_longterm: plan.sales_forecast_longterm?.length
            ? plan.sales_forecast_longterm
            : [
                  { product_service: 'Production 01', base_year: 0, proposed_year1: 0, proposed_year2: 0, proposed_year3: 0 },
                  { product_service: 'Production 02', base_year: 0, proposed_year1: 0, proposed_year2: 0, proposed_year3: 0 },
              ],

        // 10. Action Plans
        action_plans: plan.action_plans?.length
            ? plan.action_plans
            : [{ task: '', responsible_person: '', time_frame: '', status: 'Pending' }],
    });

    const submit = (e) => {
        e.preventDefault();
        if (isEdit) {
            put(`/marketing-plan/${plan.id}`);
        } else {
            post('/marketing-plan');
        }
    };

    // Generic list helpers
    const addItem = (field, defaultObj) => {
        setData(field, [...data[field], defaultObj]);
    };

    const removeItem = (field, index) => {
        if (data[field].length > 1) {
            setData(field, data[field].filter((_, i) => i !== index));
        }
    };

    const updateItem = (field, index, subkey, val) => {
        const next = [...data[field]];
        next[index][subkey] = val;
        setData(field, next);
    };

    // SWOT point helpers
    const addSwotPoint = (category) => {
        const field = `swot_${category}_list`;
        setData(field, [...data[field], '']);
    };

    const updateSwotPoint = (category, index, val) => {
        const field = `swot_${category}_list`;
        const next = [...data[field]];
        next[index] = val;
        setData(field, next);
    };

    const removeSwotPoint = (category, index) => {
        const field = `swot_${category}_list`;
        if (data[field].length > 1) {
            setData(field, data[field].filter((_, i) => i !== index));
        } else {
            setData(field, ['']);
        }
    };

    // 9.1 Calculations
    const getMonthlyRowTotal = (row) => {
        let sum = 0;
        for (let m = 1; m <= 12; m++) {
            sum += parseFloat(row[`m${m}`]) || 0;
        }
        return sum;
    };

    const getMonthlyColumnTotal = (m) => {
        return data.sales_forecast_monthly.reduce(
            (acc, row) => acc + (parseFloat(row[`m${m}`]) || 0),
            0
        );
    };

    const getMonthlyGrandTotal = () => {
        return data.sales_forecast_monthly.reduce(
            (acc, row) => acc + getMonthlyRowTotal(row),
            0
        );
    };

    // Budget Calculations
    const getBudgetTotal = (colKey) => {
        return data.marketing_expense_budget.reduce(
            (acc, row) => acc + (parseFloat(row[colKey]) || 0),
            0
        );
    };

    return (
        <Layout>
            <Head title={isEdit ? `Edit: ${data.business_name}` : 'New Marketing Plan'} />

            <div className="mb-6">
                <Link href="/marketing-plan" className="text-xs text-slate-500 hover:text-blue-600 font-semibold">
                    &larr; Back to all plans
                </Link>
                <h1 className="text-2xl font-bold text-slate-900 mt-1">
                    {isEdit ? `Edit SEDD Marketing Plan: ${data.business_name}` : 'Create SEDD MSME Practical Marketing Plan'}
                </h1>
                <p className="text-xs sm:text-sm text-slate-500">
                    Official 10-section operational document prepared by the SEDD Division Officer.
                </p>
            </div>

            <form onSubmit={submit} className="space-y-6">
                {/* Header Information Card */}
                <div className="bg-white border border-slate-200 rounded-xl p-6 shadow-xs">
                    <div className="border-b border-blue-100 pb-3 mb-5 flex items-center justify-between">
                        <h2 className="text-base font-bold text-blue-900 flex items-center gap-2">
                            <span className="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded font-bold">SEDD</span>
                            Enterprise & Entrepreneur Profile
                        </h2>
                    </div>

                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label className="block text-xs font-semibold text-slate-700 mb-1">Entrepreneur Name *</label>
                            <input
                                type="text"
                                value={data.entrepreneur_name}
                                onChange={(e) => setData('entrepreneur_name', e.target.value)}
                                required
                                className="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600"
                                placeholder="K.A. Sunimal Perera"
                            />
                        </div>
                        <div>
                            <label className="block text-xs font-semibold text-slate-700 mb-1">Address / Pre.Lt. (DS) Division *</label>
                            <input
                                type="text"
                                value={data.address_division}
                                onChange={(e) => setData('address_division', e.target.value)}
                                required
                                className="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600"
                                placeholder="Gampaha DS Division"
                            />
                        </div>
                        <div>
                            <label className="block text-xs font-semibold text-slate-700 mb-1">Telephone Number</label>
                            <input
                                type="text"
                                value={data.telephone}
                                onChange={(e) => setData('telephone', e.target.value)}
                                className="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600"
                                placeholder="077-1234567"
                            />
                        </div>
                        <div>
                            <label className="block text-xs font-semibold text-slate-700 mb-1">E-mail Address</label>
                            <input
                                type="email"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                className="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600"
                                placeholder="info@company.lk"
                            />
                        </div>
                        <div>
                            <label className="block text-xs font-semibold text-slate-700 mb-1">Business Name *</label>
                            <input
                                type="text"
                                value={data.business_name}
                                onChange={(e) => setData('business_name', e.target.value)}
                                required
                                className="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600"
                                placeholder="Ceylon Pure Agro Foods"
                            />
                        </div>
                        <div>
                            <label className="block text-xs font-semibold text-slate-700 mb-1">Registered Number (BR No.)</label>
                            <input
                                type="text"
                                value={data.registered_number}
                                onChange={(e) => setData('registered_number', e.target.value)}
                                className="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600"
                                placeholder="PV 00249812"
                            />
                        </div>
                        <div className="sm:col-span-2 lg:col-span-3">
                            <label className="block text-xs font-semibold text-slate-700 mb-1">Main Production and Service</label>
                            <input
                                type="text"
                                value={data.main_product_service_summary}
                                onChange={(e) => setData('main_product_service_summary', e.target.value)}
                                className="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600"
                                placeholder="Organic dehydrated fruits, herbal teas, and natural spices"
                            />
                        </div>
                        <div>
                            <label className="block text-xs font-semibold text-slate-700 mb-1">Plan Period: From (Date)</label>
                            <input
                                type="date"
                                value={data.plan_period_from}
                                onChange={(e) => setData('plan_period_from', e.target.value)}
                                className="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600"
                            />
                        </div>
                        <div>
                            <label className="block text-xs font-semibold text-slate-700 mb-1">Plan Period: To (12 Months)</label>
                            <input
                                type="date"
                                value={data.plan_period_to}
                                onChange={(e) => setData('plan_period_to', e.target.value)}
                                className="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600"
                            />
                        </div>
                    </div>
                </div>

                {/* 1. Purpose & Executive Summary */}
                <div className="bg-white border border-slate-200 rounded-xl p-6 shadow-xs">
                    <h2 className="text-base font-bold text-blue-900 border-b border-blue-100 pb-2 mb-3">
                        <span className="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded font-bold mr-2">1</span>
                        Purpose & Executive Summary of the Marketing Plan
                    </h2>
                    <textarea
                        rows={3}
                        value={data.purpose_executive_summary}
                        onChange={(e) => setData('purpose_executive_summary', e.target.value)}
                        className="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600"
                        placeholder="Detail the market goals, target expansion vision, and purpose of this 12-month period..."
                    />
                </div>

                {/* 2. Product Description */}
                <div className="bg-white border border-slate-200 rounded-xl p-6 shadow-xs">
                    <h2 className="text-base font-bold text-blue-900 border-b border-blue-100 pb-2 mb-4">
                        <span className="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded font-bold mr-2">2</span>
                        Product / Service Description
                    </h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label className="block text-xs font-semibold text-slate-700 mb-1">2.1 Main Product / Service</label>
                            <textarea
                                rows={3}
                                value={data.main_products_services}
                                onChange={(e) => setData('main_products_services', e.target.value)}
                                className="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600"
                                placeholder="Describe current core products, packaging, and unique selling points..."
                            />
                        </div>
                        <div>
                            <label className="block text-xs font-semibold text-slate-700 mb-1">2.2 Proposed Future Products / Services</label>
                            <textarea
                                rows={3}
                                value={data.future_products_services}
                                onChange={(e) => setData('future_products_services', e.target.value)}
                                className="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600"
                                placeholder="Describe new product lines, value additions, or service innovations planned..."
                            />
                        </div>
                    </div>
                </div>

                {/* 3. Target Market & Customers */}
                <div className="bg-white border border-slate-200 rounded-xl p-6 shadow-xs">
                    <h2 className="text-base font-bold text-blue-900 border-b border-blue-100 pb-2 mb-4">
                        <span className="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded font-bold mr-2">3</span>
                        Target Market & Customers Analysis
                    </h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label className="block text-xs font-semibold text-slate-700 mb-1">3.1 Areas Currently Covered</label>
                            <textarea
                                rows={2}
                                value={data.market_areas_current}
                                onChange={(e) => setData('market_areas_current', e.target.value)}
                                className="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg"
                                placeholder="e.g. Gampaha, Negombo, and Colombo suburbs"
                            />
                        </div>
                        <div>
                            <label className="block text-xs font-semibold text-slate-700 mb-1">3.1 Areas Expected in Future</label>
                            <textarea
                                rows={2}
                                value={data.market_areas_future}
                                onChange={(e) => setData('market_areas_future', e.target.value)}
                                className="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg"
                                placeholder="e.g. Island-wide supermarkets, Kandy tourist boutiques"
                            />
                        </div>
                    </div>

                    <div>
                        <div className="flex justify-between items-center mb-2">
                            <span className="text-xs font-bold text-slate-700">3.2 Target Customer Profile</span>
                            <button
                                type="button"
                                onClick={() => addItem('target_customer_profiles', { product_service: '', targeted_area: '', goals_customers: '' })}
                                className="text-xs text-blue-600 hover:text-blue-700 font-semibold"
                            >
                                + Add Profile Row
                            </button>
                        </div>
                        <div className="overflow-x-auto">
                            <table className="w-full text-xs text-left border border-slate-200 rounded-lg">
                                <thead className="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                                    <tr>
                                        <th className="p-2.5 w-1/4">Product / Service</th>
                                        <th className="p-2.5 w-1/3">Targeted Area</th>
                                        <th className="p-2.5">Goals / Customers (age, income, institutions)</th>
                                        <th className="p-2.5 w-10"></th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-100">
                                    {data.target_customer_profiles.map((p, idx) => (
                                        <tr key={idx}>
                                            <td className="p-2">
                                                <input
                                                    type="text"
                                                    value={p.product_service}
                                                    onChange={(e) => updateItem('target_customer_profiles', idx, 'product_service', e.target.value)}
                                                    className="w-full p-1.5 border border-slate-200 rounded text-xs"
                                                    placeholder="Product"
                                                />
                                            </td>
                                            <td className="p-2">
                                                <input
                                                    type="text"
                                                    value={p.targeted_area}
                                                    onChange={(e) => updateItem('target_customer_profiles', idx, 'targeted_area', e.target.value)}
                                                    className="w-full p-1.5 border border-slate-200 rounded text-xs"
                                                    placeholder="Area"
                                                />
                                            </td>
                                            <td className="p-2">
                                                <input
                                                    type="text"
                                                    value={p.goals_customers}
                                                    onChange={(e) => updateItem('target_customer_profiles', idx, 'goals_customers', e.target.value)}
                                                    className="w-full p-1.5 border border-slate-200 rounded text-xs"
                                                    placeholder="Target audience details"
                                                />
                                            </td>
                                            <td className="p-2 text-center">
                                                <button
                                                    type="button"
                                                    onClick={() => removeItem('target_customer_profiles', idx)}
                                                    className="text-rose-500 hover:text-rose-700 font-bold"
                                                >
                                                    &times;
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {/* 4. Competitor Analysis & Supply Capacity */}
                <div className="bg-white border border-slate-200 rounded-xl p-6 shadow-xs">
                    <h2 className="text-base font-bold text-blue-900 border-b border-blue-100 pb-2 mb-4">
                        <span className="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded font-bold mr-2">4</span>
                        Practical Market & Competitor Analysis
                    </h2>

                    {/* 4.1 Competitors */}
                    <div className="mb-6">
                        <div className="flex justify-between items-center mb-2">
                            <span className="text-xs font-bold text-slate-700">4.1 Competitor Assessment</span>
                            <button
                                type="button"
                                onClick={() => addItem('competitors', { name: '', active_area: '', key_strengths: '', weaknesses_opportunities: '' })}
                                className="text-xs text-blue-600 hover:text-blue-700 font-semibold"
                            >
                                + Add Competitor
                            </button>
                        </div>
                        <div className="overflow-x-auto">
                            <table className="w-full text-xs text-left border border-slate-200 rounded-lg">
                                <thead className="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                                    <tr>
                                        <th className="p-2.5 w-1/4">Competitor Name</th>
                                        <th className="p-2.5 w-1/4">Active Area</th>
                                        <th className="p-2.5 w-1/4">Their Key Strength</th>
                                        <th className="p-2.5">Weaknesses / Our Opportunities</th>
                                        <th className="p-2.5 w-10"></th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-100">
                                    {data.competitors.map((c, idx) => (
                                        <tr key={idx}>
                                            <td className="p-2">
                                                <input
                                                    type="text"
                                                    value={c.name}
                                                    onChange={(e) => updateItem('competitors', idx, 'name', e.target.value)}
                                                    className="w-full p-1.5 border border-slate-200 rounded text-xs"
                                                    placeholder="Competitor Name"
                                                />
                                            </td>
                                            <td className="p-2">
                                                <input
                                                    type="text"
                                                    value={c.active_area}
                                                    onChange={(e) => updateItem('competitors', idx, 'active_area', e.target.value)}
                                                    className="w-full p-1.5 border border-slate-200 rounded text-xs"
                                                    placeholder="Active Area"
                                                />
                                            </td>
                                            <td className="p-2">
                                                <input
                                                    type="text"
                                                    value={c.key_strengths}
                                                    onChange={(e) => updateItem('competitors', idx, 'key_strengths', e.target.value)}
                                                    className="w-full p-1.5 border border-slate-200 rounded text-xs"
                                                    placeholder="Strengths"
                                                />
                                            </td>
                                            <td className="p-2">
                                                <input
                                                    type="text"
                                                    value={c.weaknesses_opportunities}
                                                    onChange={(e) => updateItem('competitors', idx, 'weaknesses_opportunities', e.target.value)}
                                                    className="w-full p-1.5 border border-slate-200 rounded text-xs"
                                                    placeholder="Opportunities we have"
                                                />
                                            </td>
                                            <td className="p-2 text-center">
                                                <button
                                                    type="button"
                                                    onClick={() => removeItem('competitors', idx)}
                                                    className="text-rose-500 hover:text-rose-700 font-bold"
                                                >
                                                    &times;
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {/* 4.2 Supply Capacity */}
                    <div>
                        <div className="flex justify-between items-center mb-2">
                            <span className="text-xs font-bold text-slate-700">4.2 Proposed Supply Capacity</span>
                            <button
                                type="button"
                                onClick={() => addItem('supply_capacities', { product_service: '', demand_nature: 'Medium', monthly_capacity: '', annual_capacity: '' })}
                                className="text-xs text-blue-600 hover:text-blue-700 font-semibold"
                            >
                                + Add Supply Capacity
                            </button>
                        </div>
                        <div className="overflow-x-auto">
                            <table className="w-full text-xs text-left border border-slate-200 rounded-lg">
                                <thead className="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                                    <tr>
                                        <th className="p-2.5 w-1/3">Product / Service</th>
                                        <th className="p-2.5 w-1/5">In Demand Nature</th>
                                        <th className="p-2.5 w-1/4">Monthly Capacity</th>
                                        <th className="p-2.5">Annual Capacity (units / Rs.)</th>
                                        <th className="p-2.5 w-10"></th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-100">
                                    {data.supply_capacities.map((sc, idx) => (
                                        <tr key={idx}>
                                            <td className="p-2">
                                                <input
                                                    type="text"
                                                    value={sc.product_service}
                                                    onChange={(e) => updateItem('supply_capacities', idx, 'product_service', e.target.value)}
                                                    className="w-full p-1.5 border border-slate-200 rounded text-xs"
                                                    placeholder="Product"
                                                />
                                            </td>
                                            <td className="p-2">
                                                <select
                                                    value={sc.demand_nature}
                                                    onChange={(e) => updateItem('supply_capacities', idx, 'demand_nature', e.target.value)}
                                                    className="w-full p-1.5 border border-slate-200 rounded text-xs bg-white"
                                                >
                                                    <option value="High">High</option>
                                                    <option value="Medium">Medium</option>
                                                    <option value="Low">Low</option>
                                                </select>
                                            </td>
                                            <td className="p-2">
                                                <input
                                                    type="text"
                                                    value={sc.monthly_capacity}
                                                    onChange={(e) => updateItem('supply_capacities', idx, 'monthly_capacity', e.target.value)}
                                                    className="w-full p-1.5 border border-slate-200 rounded text-xs"
                                                    placeholder="e.g. 5,000 packs"
                                                />
                                            </td>
                                            <td className="p-2">
                                                <input
                                                    type="text"
                                                    value={sc.annual_capacity}
                                                    onChange={(e) => updateItem('supply_capacities', idx, 'annual_capacity', e.target.value)}
                                                    className="w-full p-1.5 border border-slate-200 rounded text-xs"
                                                    placeholder="e.g. 60,000 units / Rs. 3.6M"
                                                />
                                            </td>
                                            <td className="p-2 text-center">
                                                <button
                                                    type="button"
                                                    onClick={() => removeItem('supply_capacities', idx)}
                                                    className="text-rose-500 hover:text-rose-700 font-bold"
                                                >
                                                    &times;
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {/* 5. 7 Ps Comparison */}
                <div className="bg-white border border-slate-200 rounded-xl p-6 shadow-xs">
                    <h2 className="text-base font-bold text-blue-900 border-b border-blue-100 pb-2 mb-3">
                        <span className="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded font-bold mr-2">5</span>
                        Competitive Analysis (Competitor Comparison - 7 Ps)
                    </h2>
                    <div className="overflow-x-auto">
                        <table className="w-full text-xs text-left border border-slate-200 rounded-lg">
                            <thead className="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                                <tr>
                                    <th className="p-2.5 w-1/5">7 Ps Factors</th>
                                    <th className="p-2.5 w-1/3 bg-blue-50/50 text-blue-900">Our Business</th>
                                    <th className="p-2.5 w-1/4">Competitor 01</th>
                                    <th className="p-2.5 w-1/4">Competitor 02</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100">
                                {data.competitive_7ps.map((p, idx) => (
                                    <tr key={idx}>
                                        <td className="p-2.5 font-bold text-slate-700">{p.factor}</td>
                                        <td className="p-2 bg-blue-50/20">
                                            <textarea
                                                rows={2}
                                                value={p.our_business}
                                                onChange={(e) => updateItem('competitive_7ps', idx, 'our_business', e.target.value)}
                                                className="w-full p-1.5 border border-slate-200 rounded text-xs"
                                                placeholder="Our feature/approach..."
                                            />
                                        </td>
                                        <td className="p-2">
                                            <textarea
                                                rows={2}
                                                value={p.competitor_01}
                                                onChange={(e) => updateItem('competitive_7ps', idx, 'competitor_01', e.target.value)}
                                                className="w-full p-1.5 border border-slate-200 rounded text-xs"
                                                placeholder="Competitor 1 approach..."
                                            />
                                        </td>
                                        <td className="p-2">
                                            <textarea
                                                rows={2}
                                                value={p.competitor_02}
                                                onChange={(e) => updateItem('competitive_7ps', idx, 'competitor_02', e.target.value)}
                                                className="w-full p-1.5 border border-slate-200 rounded text-xs"
                                                placeholder="Competitor 2 approach..."
                                            />
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>

                {/* 6. SWOT Analysis (Itemized + Dynamic Buttons) */}
                <div className="bg-white border border-slate-200 rounded-xl p-6 shadow-xs">
                    <h2 className="text-base font-bold text-blue-900 border-b border-blue-100 pb-2 mb-4">
                        <span className="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded font-bold mr-2">6</span>
                        SWOT Analysis
                    </h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {/* Strengths */}
                        <div className="bg-emerald-50/60 border border-emerald-200 rounded-xl p-4">
                            <div className="flex justify-between items-center mb-3">
                                <span className="font-bold text-xs sm:text-sm text-emerald-800">
                                    Strengths (Internal)
                                </span>
                                <button
                                    type="button"
                                    onClick={() => addSwotPoint('strengths')}
                                    className="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-2 py-0.5 rounded shadow-xs"
                                >
                                    +
                                </button>
                            </div>
                            <div className="space-y-2">
                                {data.swot_strengths_list.map((pt, idx) => (
                                    <div key={idx} className="flex items-center gap-1.5">
                                        <span className="text-emerald-700 font-bold">•</span>
                                        <input
                                            type="text"
                                            value={pt}
                                            onChange={(e) => updateSwotPoint('strengths', idx, e.target.value)}
                                            placeholder="Enter strength..."
                                            className="w-full bg-white px-2.5 py-1.5 border border-emerald-200 rounded-md text-xs"
                                        />
                                        <button
                                            type="button"
                                            onClick={() => removeSwotPoint('strengths', idx)}
                                            className="text-rose-500 hover:text-rose-700 font-bold px-1"
                                        >
                                            &times;
                                        </button>
                                    </div>
                                ))}
                            </div>
                            <button
                                type="button"
                                onClick={() => addSwotPoint('strengths')}
                                className="mt-2.5 text-xs text-emerald-700 font-semibold hover:underline"
                            >
                                + Add Strength Point
                            </button>
                        </div>

                        {/* Weaknesses */}
                        <div className="bg-rose-50/60 border border-rose-200 rounded-xl p-4">
                            <div className="flex justify-between items-center mb-3">
                                <span className="font-bold text-xs sm:text-sm text-rose-800">
                                    Weaknesses (Internal)
                                </span>
                                <button
                                    type="button"
                                    onClick={() => addSwotPoint('weaknesses')}
                                    className="bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-2 py-0.5 rounded shadow-xs"
                                >
                                    +
                                </button>
                            </div>
                            <div className="space-y-2">
                                {data.swot_weaknesses_list.map((pt, idx) => (
                                    <div key={idx} className="flex items-center gap-1.5">
                                        <span className="text-rose-700 font-bold">•</span>
                                        <input
                                            type="text"
                                            value={pt}
                                            onChange={(e) => updateSwotPoint('weaknesses', idx, e.target.value)}
                                            placeholder="Enter weakness..."
                                            className="w-full bg-white px-2.5 py-1.5 border border-rose-200 rounded-md text-xs"
                                        />
                                        <button
                                            type="button"
                                            onClick={() => removeSwotPoint('weaknesses', idx)}
                                            className="text-rose-500 hover:text-rose-700 font-bold px-1"
                                        >
                                            &times;
                                        </button>
                                    </div>
                                ))}
                            </div>
                            <button
                                type="button"
                                onClick={() => addSwotPoint('weaknesses')}
                                className="mt-2.5 text-xs text-rose-700 font-semibold hover:underline"
                            >
                                + Add Weakness Point
                            </button>
                        </div>

                        {/* Opportunities */}
                        <div className="bg-blue-50/60 border border-blue-200 rounded-xl p-4">
                            <div className="flex justify-between items-center mb-3">
                                <span className="font-bold text-xs sm:text-sm text-blue-800">
                                    Opportunities (External)
                                </span>
                                <button
                                    type="button"
                                    onClick={() => addSwotPoint('opportunities')}
                                    className="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-2 py-0.5 rounded shadow-xs"
                                >
                                    +
                                </button>
                            </div>
                            <div className="space-y-2">
                                {data.swot_opportunities_list.map((pt, idx) => (
                                    <div key={idx} className="flex items-center gap-1.5">
                                        <span className="text-blue-700 font-bold">•</span>
                                        <input
                                            type="text"
                                            value={pt}
                                            onChange={(e) => updateSwotPoint('opportunities', idx, e.target.value)}
                                            placeholder="Enter opportunity..."
                                            className="w-full bg-white px-2.5 py-1.5 border border-blue-200 rounded-md text-xs"
                                        />
                                        <button
                                            type="button"
                                            onClick={() => removeSwotPoint('opportunities', idx)}
                                            className="text-rose-500 hover:text-rose-700 font-bold px-1"
                                        >
                                            &times;
                                        </button>
                                    </div>
                                ))}
                            </div>
                            <button
                                type="button"
                                onClick={() => addSwotPoint('opportunities')}
                                className="mt-2.5 text-xs text-blue-700 font-semibold hover:underline"
                            >
                                + Add Opportunity Point
                            </button>
                        </div>

                        {/* Threats */}
                        <div className="bg-amber-50/60 border border-amber-200 rounded-xl p-4">
                            <div className="flex justify-between items-center mb-3">
                                <span className="font-bold text-xs sm:text-sm text-amber-800">
                                    Threats (External)
                                </span>
                                <button
                                    type="button"
                                    onClick={() => addSwotPoint('threats')}
                                    className="bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs px-2 py-0.5 rounded shadow-xs"
                                >
                                    +
                                </button>
                            </div>
                            <div className="space-y-2">
                                {data.swot_threats_list.map((pt, idx) => (
                                    <div key={idx} className="flex items-center gap-1.5">
                                        <span className="text-amber-700 font-bold">•</span>
                                        <input
                                            type="text"
                                            value={pt}
                                            onChange={(e) => updateSwotPoint('threats', idx, e.target.value)}
                                            placeholder="Enter threat..."
                                            className="w-full bg-white px-2.5 py-1.5 border border-amber-200 rounded-md text-xs"
                                        />
                                        <button
                                            type="button"
                                            onClick={() => removeSwotPoint('threats', idx)}
                                            className="text-rose-500 hover:text-rose-700 font-bold px-1"
                                        >
                                            &times;
                                        </button>
                                    </div>
                                ))}
                            </div>
                            <button
                                type="button"
                                onClick={() => addSwotPoint('threats')}
                                className="mt-2.5 text-xs text-amber-700 font-semibold hover:underline"
                            >
                                + Add Threat Point
                            </button>
                        </div>
                    </div>
                </div>

                {/* 7. Suggested Marketing Strategies (4 Ps) */}
                <div className="bg-white border border-slate-200 rounded-xl p-6 shadow-xs">
                    <div className="flex justify-between items-center border-b border-blue-100 pb-2 mb-3">
                        <h2 className="text-base font-bold text-blue-900">
                            <span className="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded font-bold mr-2">7</span>
                            Suggested Marketing Strategies (4 Ps)
                        </h2>
                        <button
                            type="button"
                            onClick={() => addItem('strategies_4ps', { product: '', price: '', place: '', promotion: '' })}
                            className="text-xs text-blue-600 hover:text-blue-700 font-semibold"
                        >
                            + Add Strategy Row
                        </button>
                    </div>
                    <div className="overflow-x-auto">
                        <table className="w-full text-xs text-left border border-slate-200 rounded-lg">
                            <thead className="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                                <tr>
                                    <th className="p-2.5 w-1/4">Product Strategy</th>
                                    <th className="p-2.5 w-1/4">Price Strategy</th>
                                    <th className="p-2.5 w-1/4">Place Strategy</th>
                                    <th className="p-2.5">Promotion Strategy</th>
                                    <th className="p-2.5 w-10"></th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100">
                                {data.strategies_4ps.map((s, idx) => (
                                    <tr key={idx}>
                                        <td className="p-2">
                                            <textarea
                                                rows={2}
                                                value={s.product}
                                                onChange={(e) => updateItem('strategies_4ps', idx, 'product', e.target.value)}
                                                className="w-full p-1.5 border border-slate-200 rounded text-xs"
                                                placeholder="Product features, quality..."
                                            />
                                        </td>
                                        <td className="p-2">
                                            <textarea
                                                rows={2}
                                                value={s.price}
                                                onChange={(e) => updateItem('strategies_4ps', idx, 'price', e.target.value)}
                                                className="w-full p-1.5 border border-slate-200 rounded text-xs"
                                                placeholder="Pricing structure, margins..."
                                            />
                                        </td>
                                        <td className="p-2">
                                            <textarea
                                                rows={2}
                                                value={s.place}
                                                onChange={(e) => updateItem('strategies_4ps', idx, 'place', e.target.value)}
                                                className="w-full p-1.5 border border-slate-200 rounded text-xs"
                                                placeholder="Distribution channels..."
                                            />
                                        </td>
                                        <td className="p-2">
                                            <textarea
                                                rows={2}
                                                value={s.promotion}
                                                onChange={(e) => updateItem('strategies_4ps', idx, 'promotion', e.target.value)}
                                                className="w-full p-1.5 border border-slate-200 rounded text-xs"
                                                placeholder="Campaigns, direct selling..."
                                            />
                                        </td>
                                        <td className="p-2 text-center">
                                            <button
                                                type="button"
                                                onClick={() => removeItem('strategies_4ps', idx)}
                                                className="text-rose-500 hover:text-rose-700 font-bold"
                                            >
                                                &times;
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>

                {/* 8. Marketing Expense Budget */}
                <div className="bg-white border border-slate-200 rounded-xl p-6 shadow-xs">
                    <h2 className="text-base font-bold text-blue-900 border-b border-blue-100 pb-2 mb-3">
                        <span className="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded font-bold mr-2">8</span>
                        Marketing Expense Budget
                    </h2>
                    <div className="overflow-x-auto">
                        <table className="w-full text-xs text-left border border-slate-200 rounded-lg">
                            <thead className="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                                <tr>
                                    <th className="p-2.5 w-1/3">Expenses Item</th>
                                    <th className="p-2.5 text-right w-1/6">Existing Year (Rs.)</th>
                                    <th className="p-2.5 text-right w-1/6">Proposed 1 Year (Rs.)</th>
                                    <th className="p-2.5 text-right w-1/6">Proposed 2 Years (Rs.)</th>
                                    <th className="p-2.5 text-right w-1/6">Proposed 3 Years (Rs.)</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100">
                                {data.marketing_expense_budget.map((b, idx) => (
                                    <tr key={idx}>
                                        <td className="p-2 font-medium text-slate-800">
                                            <input
                                                type="text"
                                                value={b.item}
                                                onChange={(e) => updateItem('marketing_expense_budget', idx, 'item', e.target.value)}
                                                className="w-full p-1 border border-slate-200 rounded text-xs"
                                            />
                                        </td>
                                        {['existing_year', 'proposed_year1', 'proposed_year2', 'proposed_year3'].map((col) => (
                                            <td key={col} className="p-2 text-right">
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    value={b[col]}
                                                    onChange={(e) => updateItem('marketing_expense_budget', idx, col, parseFloat(e.target.value) || 0)}
                                                    className="w-full p-1 border border-slate-200 rounded text-xs text-right font-mono"
                                                />
                                            </td>
                                        ))}
                                    </tr>
                                ))}
                                <tr className="bg-slate-100 font-bold border-t border-slate-300">
                                    <td className="p-2.5 text-slate-800">Collection (Total)</td>
                                    {['existing_year', 'proposed_year1', 'proposed_year2', 'proposed_year3'].map((col) => (
                                        <td key={col} className="p-2.5 text-right font-mono text-slate-900">
                                            {formatNum(getBudgetTotal(col))}
                                        </td>
                                    ))}
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {/* 9. Sales Income Forecast (9.1 All 12 Months + 9.2 Long-Term) */}
                <div className="bg-white border border-slate-200 rounded-xl p-6 shadow-xs">
                    <h2 className="text-base font-bold text-blue-900 border-b border-blue-100 pb-2 mb-4">
                        <span className="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded font-bold mr-2">9</span>
                        Sales Income Forecast
                    </h2>

                    {/* 9.1 Monthly */}
                    <div className="mb-6">
                        <div className="flex justify-between items-center mb-2">
                            <div>
                                <span className="text-xs font-bold text-slate-800">
                                    9.1 Estimated monthly sales revenue for the proposed 1st year (Rs.)
                                </span>
                                <p className="text-[11px] text-slate-400">All 12 individual months with auto-calculated row and column totals.</p>
                            </div>
                            <button
                                type="button"
                                onClick={() =>
                                    addItem('sales_forecast_monthly', {
                                        product_service: `Product ${String.fromCharCode(65 + data.sales_forecast_monthly.length)}`,
                                        m1: 0, m2: 0, m3: 0, m4: 0, m5: 0, m6: 0, m7: 0, m8: 0, m9: 0, m10: 0, m11: 0, m12: 0,
                                    })
                                }
                                className="text-xs text-blue-600 hover:text-blue-700 font-semibold"
                            >
                                + Add Product Row
                            </button>
                        </div>

                        <div className="overflow-x-auto">
                            <table className="w-full text-xs text-left border border-slate-200 rounded-lg min-w-[950px]">
                                <thead className="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                                    <tr>
                                        <th className="p-2 min-w-[130px]">Product / Service</th>
                                        {MONTH_NAMES.map((name, i) => (
                                            <th key={name} className="p-2 text-right min-w-[62px]">
                                                {name}
                                            </th>
                                        ))}
                                        <th className="p-2 text-right bg-blue-100/60 text-blue-900 font-bold min-w-[85px]">
                                            1 Year Total
                                        </th>
                                        <th className="p-2 w-8"></th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-100">
                                    {data.sales_forecast_monthly.map((row, idx) => (
                                        <tr key={idx}>
                                            <td className="p-1.5 font-medium">
                                                <input
                                                    type="text"
                                                    value={row.product_service}
                                                    onChange={(e) => updateItem('sales_forecast_monthly', idx, 'product_service', e.target.value)}
                                                    className="w-full p-1 border border-slate-200 rounded text-xs"
                                                />
                                            </td>
                                            {MONTH_NAMES.map((_, mIdx) => {
                                                const mNum = mIdx + 1;
                                                return (
                                                    <td key={mNum} className="p-1 text-right">
                                                        <input
                                                            type="number"
                                                            step="0.01"
                                                            value={row[`m${mNum}`] || 0}
                                                            onChange={(e) =>
                                                                updateItem('sales_forecast_monthly', idx, `m${mNum}`, parseFloat(e.target.value) || 0)
                                                            }
                                                            className="w-full p-1 border border-slate-200 rounded text-xs text-right font-mono"
                                                        />
                                                    </td>
                                                );
                                            })}
                                            <td className="p-2 text-right font-bold font-mono bg-blue-50/50 text-blue-900">
                                                {formatNum(getMonthlyRowTotal(row))}
                                            </td>
                                            <td className="p-1 text-center">
                                                <button
                                                    type="button"
                                                    onClick={() => removeItem('sales_forecast_monthly', idx)}
                                                    className="text-rose-500 hover:text-rose-700 font-bold"
                                                >
                                                    &times;
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                    <tr className="bg-slate-100 font-bold border-t border-slate-300">
                                        <td className="p-2 text-slate-800">Total Income (Rs.)</td>
                                        {MONTH_NAMES.map((_, mIdx) => (
                                            <td key={mIdx} className="p-2 text-right font-mono text-slate-900">
                                                {formatNum(getMonthlyColumnTotal(mIdx + 1))}
                                            </td>
                                        ))}
                                        <td className="p-2 text-right font-mono bg-blue-100 text-blue-900 font-extrabold">
                                            {formatNum(getMonthlyGrandTotal())}
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {/* 9.2 Long-Term */}
                    <div>
                        <div className="flex justify-between items-center mb-2">
                            <span className="text-xs font-bold text-slate-800">
                                9.2 Long-term (3-year) sales revenue estimate
                            </span>
                            <button
                                type="button"
                                onClick={() =>
                                    addItem('sales_forecast_longterm', {
                                        product_service: `Production 0${data.sales_forecast_longterm.length + 1}`,
                                        base_year: 0, proposed_year1: 0, proposed_year2: 0, proposed_year3: 0,
                                    })
                                }
                                className="text-xs text-blue-600 hover:text-blue-700 font-semibold"
                            >
                                + Add Long-Term Row
                            </button>
                        </div>
                        <div className="overflow-x-auto">
                            <table className="w-full text-xs text-left border border-slate-200 rounded-lg">
                                <thead className="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                                    <tr>
                                        <th className="p-2.5 w-1/3">Product / Service</th>
                                        <th className="p-2.5 text-right w-1/6">Foundations Year (Rs.)</th>
                                        <th className="p-2.5 text-right w-1/6">Proposed 1 Year (Rs.)</th>
                                        <th className="p-2.5 text-right w-1/6">Proposed 2 Years (Rs.)</th>
                                        <th className="p-2.5 text-right w-1/6">Proposed 3 Years (Rs.)</th>
                                        <th className="p-2.5 w-8"></th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-100">
                                    {data.sales_forecast_longterm.map((lt, idx) => (
                                        <tr key={idx}>
                                            <td className="p-2">
                                                <input
                                                    type="text"
                                                    value={lt.product_service}
                                                    onChange={(e) => updateItem('sales_forecast_longterm', idx, 'product_service', e.target.value)}
                                                    className="w-full p-1 border border-slate-200 rounded text-xs"
                                                />
                                            </td>
                                            {['base_year', 'proposed_year1', 'proposed_year2', 'proposed_year3'].map((col) => (
                                                <td key={col} className="p-2 text-right">
                                                    <input
                                                        type="number"
                                                        step="0.01"
                                                        value={lt[col] || 0}
                                                        onChange={(e) =>
                                                            updateItem('sales_forecast_longterm', idx, col, parseFloat(e.target.value) || 0)
                                                        }
                                                        className="w-full p-1 border border-slate-200 rounded text-xs text-right font-mono"
                                                    />
                                                </td>
                                            ))}
                                            <td className="p-1 text-center">
                                                <button
                                                    type="button"
                                                    onClick={() => removeItem('sales_forecast_longterm', idx)}
                                                    className="text-rose-500 hover:text-rose-700 font-bold"
                                                >
                                                    &times;
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {/* 10. Action Plan & Monitoring */}
                <div className="bg-white border border-slate-200 rounded-xl p-6 shadow-xs">
                    <div className="flex justify-between items-center border-b border-blue-100 pb-2 mb-3">
                        <h2 className="text-base font-bold text-blue-900">
                            <span className="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded font-bold mr-2">10</span>
                            Action Plan & Monitoring
                        </h2>
                        <button
                            type="button"
                            onClick={() => addItem('action_plans', { task: '', responsible_person: '', time_frame: '', status: 'Pending' })}
                            className="text-xs text-blue-600 hover:text-blue-700 font-semibold"
                        >
                            + Add Action Row
                        </button>
                    </div>
                    <div className="overflow-x-auto">
                        <table className="w-full text-xs text-left border border-slate-200 rounded-lg">
                            <thead className="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                                <tr>
                                    <th className="p-2.5 w-2/5">Task / Activity</th>
                                    <th className="p-2.5 w-1/4">Responsible Person</th>
                                    <th className="p-2.5 w-1/5">Time Frame</th>
                                    <th className="p-2.5">Progress / Status</th>
                                    <th className="p-2.5 w-8"></th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100">
                                {data.action_plans.map((act, idx) => (
                                    <tr key={idx}>
                                        <td className="p-2">
                                            <input
                                                type="text"
                                                value={act.task}
                                                onChange={(e) => updateItem('action_plans', idx, 'task', e.target.value)}
                                                className="w-full p-1.5 border border-slate-200 rounded text-xs"
                                                placeholder="Action step or milestone"
                                            />
                                        </td>
                                        <td className="p-2">
                                            <input
                                                type="text"
                                                value={act.responsible_person}
                                                onChange={(e) => updateItem('action_plans', idx, 'responsible_person', e.target.value)}
                                                className="w-full p-1.5 border border-slate-200 rounded text-xs"
                                                placeholder="Person / Role"
                                            />
                                        </td>
                                        <td className="p-2">
                                            <input
                                                type="text"
                                                value={act.time_frame}
                                                onChange={(e) => updateItem('action_plans', idx, 'time_frame', e.target.value)}
                                                className="w-full p-1.5 border border-slate-200 rounded text-xs"
                                                placeholder="Month 1 - 3"
                                            />
                                        </td>
                                        <td className="p-2">
                                            <select
                                                value={act.status}
                                                onChange={(e) => updateItem('action_plans', idx, 'status', e.target.value)}
                                                className="w-full p-1.5 border border-slate-200 rounded text-xs bg-white"
                                            >
                                                <option value="Pending">Pending</option>
                                                <option value="In Progress">In Progress</option>
                                                <option value="Completed">Completed</option>
                                            </select>
                                        </td>
                                        <td className="p-2 text-center">
                                            <button
                                                type="button"
                                                onClick={() => removeItem('action_plans', idx)}
                                                className="text-rose-500 hover:text-rose-700 font-bold"
                                            >
                                                &times;
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>

                {/* Form Action Controls */}
                <div className="flex items-center justify-between pt-4 border-t border-slate-200">
                    <Link href="/marketing-plan" className="text-xs font-semibold text-slate-500 hover:text-slate-800">
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        disabled={processing}
                        className="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm px-6 py-2.5 rounded-lg shadow-xs transition-colors cursor-pointer"
                    >
                        {processing ? 'Saving...' : isEdit ? '💾 Update SEDD Marketing Plan' : '💾 Save Marketing Plan'}
                    </button>
                </div>
            </form>
        </Layout>
    );
}
