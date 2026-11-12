/**
 * Utility functions for calculations and formatting
 * Kept framework-agnostic so they can be easily shared with React Native in the future.
 */

export function num(val) {
    const n = parseFloat(val);
    return isNaN(n) ? 0 : n;
}

export function formatNum(val) {
    const n = parseFloat(val) || 0;
    return n.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

export function formatRs(val) {
    const n = parseFloat(val) || 0;
    return n.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

export function formatInteger(val) {
    const n = parseInt(val, 10) || 0;
    return n.toLocaleString('en-US');
}

export function evenSplit(total, parts) {
    if (parts <= 0) return [];
    const base = Math.floor(total / parts);
    const extra = total % parts;
    const out = [];
    for (let i = 0; i < parts; i++) {
        out.push(base + (i < extra ? 1 : 0));
    }
    return out;
}

export const MONTH_NAMES = [
    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
];

export const MONTHS = MONTH_NAMES;
