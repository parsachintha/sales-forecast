import React from 'react';
import { useForm, Link, Head } from '@inertiajs/react';

export default function Register() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        email: '',
        role: 'company',
        organization_name: '',
        password: '',
        password_confirmation: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post('/register');
    };

    return (
        <div className="min-h-screen bg-gradient-to-br from-blue-50 via-slate-50 to-indigo-50 flex items-center justify-center p-4">
            <Head title="Create Account" />

            <div className="w-full max-w-md">
                <div className="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-xl shadow-slate-200/50">
                    <div className="text-center mb-6">
                        <div className="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 text-white font-extrabold flex items-center justify-center text-lg rounded-2xl mx-auto mb-3 shadow-md shadow-blue-500/20">
                            SEDD
                        </div>
                        <h1 className="text-xl font-bold text-slate-900 tracking-tight">
                            Create User Account
                        </h1>
                        <p className="text-xs sm:text-sm text-slate-500 mt-1">
                            Register as a Company Manager or SEDD Division Officer
                        </p>
                    </div>

                    <form onSubmit={submit} className="space-y-3.5">
                        <div>
                            <label className="block text-xs sm:text-sm font-semibold text-slate-700 mb-1">
                                Full Name
                            </label>
                            <input
                                type="text"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                required
                                autoFocus
                                className="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm"
                                placeholder="e.g. Kamal Perera"
                            />
                            {errors.name && <p className="text-xs text-rose-600 mt-1">{errors.name}</p>}
                        </div>

                        <div>
                            <label className="block text-xs sm:text-sm font-semibold text-slate-700 mb-1">
                                E-mail Address
                            </label>
                            <input
                                type="email"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                required
                                className="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm"
                                placeholder="kamal@business.lk"
                            />
                            {errors.email && <p className="text-xs text-rose-600 mt-1">{errors.email}</p>}
                        </div>

                        <div>
                            <label className="block text-xs sm:text-sm font-semibold text-slate-700 mb-1">
                                Account Role *
                            </label>
                            <select
                                value={data.role}
                                onChange={(e) => setData('role', e.target.value)}
                                required
                                className="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm bg-white"
                            >
                                <option value="company">🏢 Company Team / Manager (Sets goals & allocates to reps)</option>
                                <option value="officer">🏛️ SEDD Division Officer (Creates MSME Marketing Plans)</option>
                            </select>
                            {errors.role && <p className="text-xs text-rose-600 mt-1">{errors.role}</p>}
                        </div>

                        <div>
                            <label className="block text-xs sm:text-sm font-semibold text-slate-700 mb-1">
                                Business / Division Name
                            </label>
                            <input
                                type="text"
                                value={data.organization_name}
                                onChange={(e) => setData('organization_name', e.target.value)}
                                className="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm"
                                placeholder="e.g. Ceylon Agro Foods or Gampaha Division"
                            />
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
                                className="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm"
                                placeholder="••••••••"
                            />
                            {errors.password && <p className="text-xs text-rose-600 mt-1">{errors.password}</p>}
                        </div>

                        <div>
                            <label className="block text-xs sm:text-sm font-semibold text-slate-700 mb-1">
                                Confirm Password
                            </label>
                            <input
                                type="password"
                                value={data.password_confirmation}
                                onChange={(e) => setData('password_confirmation', e.target.value)}
                                required
                                className="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm"
                                placeholder="••••••••"
                            />
                        </div>

                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors shadow-xs disabled:opacity-60 cursor-pointer mt-2"
                        >
                            {processing ? 'Registering...' : 'Register & Sign In'}
                        </button>
                    </form>

                    <div className="text-center mt-5 pt-3 border-t border-slate-100 text-xs text-slate-500">
                        Already have an account?{' '}
                        <Link href="/login" className="text-blue-600 hover:underline font-semibold">
                            Sign In
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    );
}
