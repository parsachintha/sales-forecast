import React from 'react';
import { useForm, Link, Head } from '@inertiajs/react';

export default function Login({ status }) {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();
        post('/login');
    };

    return (
        <div className="min-h-screen bg-gradient-to-br from-blue-50 via-slate-50 to-indigo-50 flex items-center justify-center p-4">
            <Head title="Sign In" />

            <div className="w-full max-w-md">
                <div className="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-xl shadow-slate-200/50">
                    {/* Header */}
                    <div className="text-center mb-6">
                        <div className="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 text-white font-extrabold flex items-center justify-center text-lg rounded-2xl mx-auto mb-3 shadow-md shadow-blue-500/20">
                            SEDD
                        </div>
                        <h1 className="text-xl font-bold text-slate-900 tracking-tight">
                            Sign In to SEDD System
                        </h1>
                        <p className="text-xs sm:text-sm text-slate-500 mt-1">
                            Small Enterprise Development Division &ndash; MSME Practical Marketing Plan
                        </p>
                    </div>

                    {status && (
                        <div className="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm p-3 rounded-lg">
                            {status}
                        </div>
                    )}

                    <form onSubmit={submit} className="space-y-4">
                        <div>
                            <label className="block text-xs sm:text-sm font-semibold text-slate-700 mb-1">
                                E-mail Address
                            </label>
                            <input
                                type="email"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                required
                                autoFocus
                                className="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm"
                                placeholder="name@company.lk"
                            />
                            {errors.email && (
                                <p className="text-xs text-rose-600 mt-1 font-medium">{errors.email}</p>
                            )}
                        </div>

                        <div>
                            <label className="block text-xs sm:text-sm font-semibold text-slate-700 mb-1">
                                Password
                            </label>
                            <input
                                type="password"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                required
                                className="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm"
                                placeholder="••••••••"
                            />
                            {errors.password && (
                                <p className="text-xs text-rose-600 mt-1 font-medium">{errors.password}</p>
                            )}
                        </div>

                        <div className="flex items-center justify-between text-xs sm:text-sm">
                            <label className="inline-flex items-center gap-2 cursor-pointer text-slate-600">
                                <input
                                    type="checkbox"
                                    checked={data.remember}
                                    onChange={(e) => setData('remember', e.target.checked)}
                                    className="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                />
                                <span>Remember me</span>
                            </label>
                        </div>

                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors shadow-xs disabled:opacity-60 cursor-pointer"
                        >
                            {processing ? 'Signing In...' : 'Sign In'}
                        </button>
                    </form>

                    <div className="text-center mt-6 pt-4 border-t border-slate-100 text-xs text-slate-500">
                        Don't have an account?{' '}
                        <Link href="/register" className="text-blue-600 hover:underline font-semibold">
                            Create an account
                        </Link>
                    </div>

                    {/* Role Demo Accounts Reference Box */}
                    <div className="mt-5 bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 text-xs text-slate-600 space-y-2">
                        <div className="font-semibold text-slate-700 border-b border-slate-200 pb-1">
                            Demo Accounts (Role Separation):
                        </div>
                        <div>
                            <span className="font-bold text-blue-700">🏛️ SEDD Division Officer:</span><br />
                            Email: <code className="bg-white px-1 py-0.5 rounded border border-slate-200">officer@sedd.lk</code> &middot; Pass: <code className="bg-white px-1 py-0.5 rounded border border-slate-200">officer123</code>
                        </div>
                        <div>
                            <span className="font-bold text-emerald-700">🏢 Company Management Team:</span><br />
                            Email: <code className="bg-white px-1 py-0.5 rounded border border-slate-200">company@agrofoods.lk</code> &middot; Pass: <code className="bg-white px-1 py-0.5 rounded border border-slate-200">company123</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
