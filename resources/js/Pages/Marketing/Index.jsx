import React from 'react';
import { Head, Link, usePage, router } from '@inertiajs/react';
import Layout from '@/Components/Layout';

export default function MarketingIndex({ plans = [] }) {
    const page = usePage();
    const auth = page.props?.auth;
    const user = auth?.user;

    const plansList = Array.isArray(plans) ? plans : [];

    const handleDelete = (id, name) => {
        if (confirm(`Are you sure you want to delete the marketing plan for "${name || 'this business'}"?`)) {
            router.delete(`/marketing-plan/${id}`);
        }
    };

    return (
        <Layout>
            <Head title="Marketing Plans" />

            <div className="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-6">
                <div>
                    <h1 className="text-2xl font-bold text-slate-900">
                        Small Enterprise Development Division (SEDD)
                    </h1>
                    <p className="text-slate-500 text-sm mt-0.5">
                        MSME Practical Marketing Plans (12-Month Operating Documents)
                    </p>
                </div>

                {user?.is_officer && (
                    <Link
                        href="/marketing-plan/create"
                        className="bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-4 py-2.5 rounded-lg shadow-xs transition-colors"
                    >
                        + Create New Marketing Plan
                    </Link>
                )}
            </div>

            <div className="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-xs">
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr className="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-xs uppercase tracking-wider">
                                <th className="px-5 py-3.5">Business Name</th>
                                <th className="px-5 py-3.5">Entrepreneur</th>
                                <th className="px-5 py-3.5">Registration No.</th>
                                <th className="px-5 py-3.5">Plan Period</th>
                                <th className="px-5 py-3.5">Main Product / Service</th>
                                <th className="px-5 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100">
                            {plansList.length > 0 ? (
                                plansList.map((plan) => (
                                    <tr key={plan.id} className="hover:bg-slate-50/80 transition-colors">
                                        <td className="px-5 py-3.5 font-bold text-slate-900">
                                            {plan.business_name || 'Untitled Business'}
                                        </td>
                                        <td className="px-5 py-3.5 text-slate-700">
                                            {plan.entrepreneur_name || '-'}
                                        </td>
                                        <td className="px-5 py-3.5 text-slate-500 font-mono text-xs">
                                            {plan.registered_number || '-'}
                                        </td>
                                        <td className="px-5 py-3.5 text-slate-600 text-xs">
                                            {plan.plan_period_from && plan.plan_period_to
                                                ? `${plan.plan_period_from.substring(0, 10)} to ${plan.plan_period_to.substring(0, 10)}`
                                                : '-'}
                                        </td>
                                        <td className="px-5 py-3.5 text-slate-600 max-w-xs truncate text-xs">
                                            {plan.main_product_service_summary || '-'}
                                        </td>
                                        <td className="px-5 py-3.5 text-right whitespace-nowrap">
                                            <div className="flex items-center justify-end gap-1.5">
                                                <Link
                                                    href={`/marketing-plan/${plan.id}`}
                                                    className="px-2.5 py-1 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-md transition-colors"
                                                >
                                                    View
                                                </Link>

                                                {user?.is_officer && (
                                                    <>
                                                        <Link
                                                            href={`/marketing-plan/${plan.id}/edit`}
                                                            className="px-2.5 py-1 text-xs font-semibold text-blue-700 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 rounded-md transition-colors"
                                                        >
                                                            Edit
                                                        </Link>
                                                        <button
                                                            onClick={() => handleDelete(plan.id, plan.business_name)}
                                                            className="px-2.5 py-1 text-xs font-semibold text-rose-700 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 rounded-md transition-colors cursor-pointer"
                                                        >
                                                            Delete
                                                        </button>
                                                    </>
                                                )}

                                                {user?.is_company && (
                                                    <Link
                                                        href={`/forecast/create?plan_id=${plan.id}`}
                                                        className="px-2.5 py-1 text-xs font-semibold text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 rounded-md transition-colors"
                                                    >
                                                        Allocate Reps
                                                    </Link>
                                                )}
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan={6} className="px-5 py-16 text-center text-slate-400">
                                        <p className="text-sm">No MSME marketing plans created yet.</p>
                                        {user?.is_officer && (
                                            <Link
                                                href="/marketing-plan/create"
                                                className="inline-block mt-3 text-xs font-semibold text-blue-600 hover:underline"
                                            >
                                                + Create the first plan
                                            </Link>
                                        )}
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </Layout>
    );
}
