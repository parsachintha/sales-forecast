import React, { useState } from 'react';
import { Link, usePage, router } from '@inertiajs/react';

export default function Layout({ children, title }) {
    const page = usePage();
    const { auth, flash } = page.props || {};
    const url = page.url || window.location.pathname || '';
    const user = auth?.user;
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

    const handleSignOut = (e) => {
        e.preventDefault();
        router.post('/logout');
    };

    const isMarketingActive = url.startsWith('/marketing-plan') || url === '/';
    const isForecastActive = url.startsWith('/forecast');

    return (
        <div className="min-h-screen bg-slate-50 flex flex-col font-sans text-slate-800 antialiased">
            {/* Header */}
            <header className="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-xs print:hidden">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between">
                    {/* Brand / Logo */}
                    <Link href="/" className="flex items-center gap-3 group text-decoration-none">
                        <div className="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-700 to-blue-600 text-white font-extrabold flex items-center justify-center text-sm shadow-sm tracking-wider">
                            SEDD
                        </div>
                        <div className="flex flex-col">
                            <span className="font-bold text-slate-900 text-sm sm:text-base leading-tight group-hover:text-blue-600 transition-colors">
                                Small Enterprise Development Division
                            </span>
                            <span className="text-xs text-slate-500 font-medium flex items-center gap-1.5">
                                <span className="bg-slate-100 text-slate-600 text-[10px] px-1.5 py-0.5 rounded font-semibold uppercase tracking-wider">
                                    Gov of Sri Lanka
                                </span>
                                MSME Practical Marketing Plan
                            </span>
                        </div>
                    </Link>

                    {/* Desktop Navigation */}
                    <div className="hidden md:flex items-center gap-4">
                        <nav className="flex items-center gap-1 bg-slate-100 p-1 rounded-lg">
                            {(!user || user.is_officer) && (
                                <Link
                                    href="/marketing-plan"
                                    className={`px-3 py-1.5 rounded-md text-xs sm:text-sm font-medium transition-all ${
                                        isMarketingActive
                                            ? 'bg-white text-blue-700 font-semibold shadow-xs'
                                            : 'text-slate-600 hover:text-slate-900'
                                    }`}
                                >
                                    📋 Marketing Plans
                                </Link>
                            )}

                            {(!user || user.is_company) && (
                                <Link
                                    href="/forecast"
                                    className={`px-3 py-1.5 rounded-md text-xs sm:text-sm font-medium transition-all ${
                                        isForecastActive
                                            ? 'bg-white text-blue-700 font-semibold shadow-xs'
                                            : 'text-slate-600 hover:text-slate-900'
                                    }`}
                                >
                                    📊 Rep Allocation & Goals
                                </Link>
                            )}

                            {user?.is_company && (
                                <Link
                                    href="/marketing-plan"
                                    className={`px-3 py-1.5 rounded-md text-xs sm:text-sm font-medium transition-all ${
                                        isMarketingActive
                                            ? 'bg-white text-blue-700 font-semibold shadow-xs'
                                            : 'text-slate-600 hover:text-slate-900'
                                    }`}
                                >
                                    📋 Reference Plans
                                </Link>
                            )}
                        </nav>

                        {/* Role-based Primary Action Button */}
                        {user?.is_officer && (
                            <Link
                                href="/marketing-plan/create"
                                className="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold px-3.5 py-2 rounded-lg shadow-xs transition-colors"
                            >
                                <span className="text-base leading-none font-bold">+</span>
                                <span>New Marketing Plan</span>
                            </Link>
                        )}

                        {user?.is_company && (
                            <Link
                                href="/forecast/create"
                                className="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold px-3.5 py-2 rounded-lg shadow-xs transition-colors"
                            >
                                <span className="text-base leading-none font-bold">+</span>
                                <span>Set Goals & Allocate Reps</span>
                            </Link>
                        )}

                        {/* User Profile / Auth Area */}
                        {user ? (
                            <div className="flex items-center gap-3 pl-3 border-l border-slate-200">
                                <div className="text-right leading-tight">
                                    <div className="text-xs sm:text-sm font-bold text-slate-800">
                                        {user.name}
                                    </div>
                                    <div
                                        className={`text-[11px] font-semibold ${
                                            user.is_officer ? 'text-blue-700' : 'text-emerald-700'
                                        }`}
                                    >
                                        {user.role_label}
                                    </div>
                                </div>
                                <button
                                    onClick={handleSignOut}
                                    className="text-xs text-rose-600 hover:text-rose-700 font-medium px-2.5 py-1.5 border border-rose-200 hover:bg-rose-50 rounded-md transition-colors cursor-pointer"
                                >
                                    Sign Out
                                </button>
                            </div>
                        ) : (
                            <div className="pl-3 border-l border-slate-200 flex items-center gap-2">
                                <Link
                                    href="/login"
                                    className="text-xs sm:text-sm font-semibold text-blue-600 hover:text-blue-700 px-3 py-1.5 rounded-md hover:bg-blue-50 transition-colors"
                                >
                                    Sign In
                                </Link>
                                <Link
                                    href="/register"
                                    className="text-xs sm:text-sm font-semibold bg-blue-600 text-white px-3 py-1.5 rounded-md hover:bg-blue-700 transition-colors shadow-xs"
                                >
                                    Register
                                </Link>
                            </div>
                        )}
                    </div>

                    {/* Mobile Hamburger Button */}
                    <div className="flex md:hidden items-center gap-2">
                        <button
                            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                            className="p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100"
                        >
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {mobileMenuOpen ? (
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                ) : (
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                                )}
                            </svg>
                        </button>
                    </div>
                </div>

                {/* Mobile Drawer */}
                {mobileMenuOpen && (
                    <div className="md:hidden border-t border-slate-200 px-4 py-3 bg-white space-y-2">
                        {(!user || user.is_officer) && (
                            <Link
                                href="/marketing-plan"
                                onClick={() => setMobileMenuOpen(false)}
                                className="block px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:bg-slate-100"
                            >
                                📋 Marketing Plans
                            </Link>
                        )}
                        {(!user || user.is_company) && (
                            <Link
                                href="/forecast"
                                onClick={() => setMobileMenuOpen(false)}
                                className="block px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:bg-slate-100"
                            >
                                📊 Rep Allocation & Goals
                            </Link>
                        )}
                        {user?.is_company && (
                            <Link
                                href="/marketing-plan"
                                onClick={() => setMobileMenuOpen(false)}
                                className="block px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:bg-slate-100"
                            >
                                📋 Reference Plans
                            </Link>
                        )}

                        {user?.is_officer && (
                            <Link
                                href="/marketing-plan/create"
                                onClick={() => setMobileMenuOpen(false)}
                                className="block text-center bg-blue-600 text-white font-semibold py-2 rounded-lg text-sm"
                            >
                                + New Marketing Plan
                            </Link>
                        )}

                        {user?.is_company && (
                            <Link
                                href="/forecast/create"
                                onClick={() => setMobileMenuOpen(false)}
                                className="block text-center bg-emerald-600 text-white font-semibold py-2 rounded-lg text-sm"
                            >
                                + Set Goals & Allocate Reps
                            </Link>
                        )}

                        <div className="pt-2 border-t border-slate-100">
                            {user ? (
                                <div className="flex items-center justify-between">
                                    <div>
                                        <div className="text-sm font-bold text-slate-800">{user.name}</div>
                                        <div className="text-xs text-slate-500">{user.role_label}</div>
                                    </div>
                                    <button
                                        onClick={handleSignOut}
                                        className="text-xs text-rose-600 font-semibold px-3 py-1.5 border border-rose-200 rounded-md"
                                    >
                                        Sign Out
                                    </button>
                                </div>
                            ) : (
                                <div className="flex gap-2">
                                    <Link href="/login" className="flex-1 text-center py-2 text-sm text-blue-600 font-semibold border border-blue-200 rounded-lg">
                                        Sign In
                                    </Link>
                                    <Link href="/register" className="flex-1 text-center py-2 text-sm bg-blue-600 text-white font-semibold rounded-lg">
                                        Register
                                    </Link>
                                </div>
                            )}
                        </div>
                    </div>
                )}
            </header>

            {/* Flash Message Banner */}
            {flash?.status && (
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full print:hidden">
                    <div className="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl flex items-center justify-between">
                        <div className="flex items-center gap-2">
                            <span>✅</span>
                            <span>{flash.status}</span>
                        </div>
                    </div>
                </div>
            )}

            {/* Main Content */}
            <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full flex-1">
                {children}
            </main>
        </div>
    );
}
