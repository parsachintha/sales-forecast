@extends('marketing.layout')

@section('title', 'SEDD - MSME Practical Marketing Plans')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom: 24px;">
    <div>
        <h1 style="font-size:24px; margin:0 0 6px;">Small Enterprise Development Division (SEDD)</h1>
        <div style="color:var(--mute); font-size:15px;">MSME Practical Marketing Plans</div>
    </div>
    @auth
        @if(Auth::user()->isOfficer())
            <a href="{{ route('marketing-plan.create') }}" class="btn">+ Create New Marketing Plan</a>
        @endif
    @else
        <a href="{{ route('login') }}" class="btn ghost">Sign In</a>
    @endauth
</div>

<div class="card" style="padding:0; overflow:hidden;">
    <table class="sedd-table" style="margin:0;">
        <thead>
            <tr>
                <th>Business Name</th>
                <th>Entrepreneur</th>
                <th>Registration No.</th>
                <th>Plan Period (12 Months)</th>
                <th>Main Product / Service</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($plans as $plan)
                <tr>
                    <td><strong>{{ $plan->business_name ?? 'Untitled Business' }}</strong></td>
                    <td>{{ $plan->entrepreneur_name ?? 'N/A' }}</td>
                    <td>{{ $plan->registered_number ?? '-' }}</td>
                    <td>
                        @if($plan->plan_period_from && $plan->plan_period_to)
                            {{ \Carbon\Carbon::parse($plan->plan_period_from)->format('Y/m/d') }} to {{ \Carbon\Carbon::parse($plan->plan_period_to)->format('Y/m/d') }}
                        @else
                            <span style="color:var(--mute);">-</span>
                        @endif
                    </td>
                    <td>{{ Str::limit($plan->main_product_service_summary, 40) ?? '-' }}</td>
                    <td style="text-align:right; white-space:nowrap;">
                        <a href="{{ route('marketing-plan.show', $plan) }}" class="btn ghost" style="padding:4px 10px; font-size:12px;">View Document</a>
                        @auth
                            @if(Auth::user()->isOfficer())
                                <a href="{{ route('marketing-plan.edit', $plan) }}" class="btn" style="padding:4px 10px; font-size:12px;">Edit</a>
                                <form action="{{ route('marketing-plan.destroy', $plan) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this marketing plan?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn danger">Delete</button>
                                </form>
                            @else
                                <a href="{{ route('forecast.create', ['plan_id' => $plan->id]) }}" class="btn" style="padding:4px 10px; font-size:12px; background:#059669; border-color:#047857;">Allocate Reps</a>
                            @endif
                        @endauth
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding: 40px; color:var(--mute);">
                        <p style="margin:0 0 14px; font-size:15px;">No MSME marketing plans created yet.</p>
                        <a href="{{ route('marketing-plan.create') }}" class="btn">+ Create Your First Marketing Plan</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
