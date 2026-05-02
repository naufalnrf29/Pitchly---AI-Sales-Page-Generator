@extends('layouts.main')

@section('title', 'Profile')

@section('breadcrumb')
    <span class="text-gray-400">Account</span>
    <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-semibold text-gray-900">Profile</span>
@endsection

@section('content')
<div class="max-w-2xl space-y-6">

    {{-- Profile Information --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        @include('profile.partials.update-profile-information-form')
    </div>

    {{-- Update Password --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        @include('profile.partials.update-password-form')
    </div>

    {{-- Delete Account --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        @include('profile.partials.delete-user-form')
    </div>

</div>
@endsection
