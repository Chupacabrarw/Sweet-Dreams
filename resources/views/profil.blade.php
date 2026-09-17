@extends('layouts.app')

@section('title', 'Akun Saya - Sweet Dreams')

@section('content')
<style>
    /* ===== PROFILE PAGE WRAPPER ===== */
    .profile-page-wrapper {
        max-width: 1280px;
        margin: 0 auto;
        padding: 2.5rem 2rem 5rem;
    }

    /* Header */
    .profile-header {
        margin-bottom: 2.5rem;
    }
    .profile-eyebrow {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #b87b58;
        display: block;
        margin-bottom: 0.4rem;
    }
    .profile-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0 0 0.5rem 0;
    }
    .profile-header p {
        font-size: 0.95rem;
        color: #8a6a72;
        margin: 0;
    }

    /* Main Grid */
    .profile-layout-grid {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 2.5rem;
        align-items: start;
    }

    /* ===== SIDEBAR ===== */
    .profile-sidebar-card {
        background: #ffffff;
        border: 1.5px solid #fbd5df;
        border-radius: 24px;
        padding: 2rem 1.5rem;
        box-shadow: 0 4px 20px rgba(212, 77, 110, 0.04);
        position: sticky;
        top: 88px;
    }
    .sidebar-avatar-container {
        position: relative;
        width: 88px;
        margin: 0 auto 0.85rem;
    }
    .sidebar-avatar-box {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto;
        border: 2.5px solid #fbd5df;
        background: #fdf2f5;
        cursor: pointer;
        position: relative;
        transition: all 0.25s ease;
    }
    .sidebar-avatar-box:hover {
        border-color: #d44d6e;
        transform: scale(1.03);
        box-shadow: 0 4px 15px rgba(212, 77, 110, 0.2);
    }
    .sidebar-avatar-overlay {
        position: absolute;
        inset: 0;
        background: rgba(58, 42, 46, 0.45);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    .sidebar-avatar-box:hover .sidebar-avatar-overlay {
        opacity: 1;
    }
    .sidebar-avatar-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .sidebar-avatar-badge-btn {
        position: absolute;
        bottom: 0px;
        right: 0px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #d44d6e;
        color: #ffffff;
        border: 2px solid #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        transition: all 0.2s ease;
    }
    .sidebar-avatar-badge-btn:hover {
        background: #ba3b5d;
        transform: scale(1.1);
    }
    .sidebar-user-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.2rem;
        font-weight: 700;
        color: #3a2a2e;
        text-align: center;
        margin: 0 0 0.2rem 0;
    }
    .sidebar-user-email {
        font-size: 0.8rem;
        color: #8a6a72;
        text-align: center;
        margin: 0 0 1.75rem 0;
    }

    /* Sidebar Navigation Menu */
    .profile-nav-menu {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }
    .profile-nav-item {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
        font-weight: 500;
        color: #5a3a42;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: left;
        width: 100%;
    }
    .profile-nav-item:hover {
        background: #fdf2f5;
        color: #d44d6e;
    }
    .profile-nav-item.active {
        background: #fdf0f4;
        color: #d44d6e;
        font-weight: 700;
    }
    .profile-nav-item svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }
    .profile-nav-logout-btn {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
        font-weight: 600;
        color: #f43f5e;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: left;
        width: 100%;
        margin-top: 0.75rem;
        text-decoration: none;
        box-sizing: border-box;
    }
    .profile-nav-logout-btn:hover {
        background: #fff1f2;
        color: #e11d48;
    }
    .profile-nav-logout-btn svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    /* ===== RIGHT: CONTENT PANELS ===== */
    .profile-tab-panel {
        display: none;
        animation: fadeInTab 0.3s ease;
    }
    .profile-tab-panel.active {
        display: block;
    }
    @keyframes fadeInTab {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ===== STATS ROW (RINGKASAN) ===== */
    .stats-cards-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
        margin-bottom: 1.75rem;
    }
    .stat-card {
        background: #ffffff;
        border: 1.5px solid #fbd5df;
        border-radius: 20px;
        padding: 1.5rem 1.75rem;
        box-shadow: 0 4px 16px rgba(212, 77, 110, 0.03);
        transition: transform 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
    }
    .stat-card.highlight {
        background: #fef2f5;
        border-color: #fbd5df;
    }
    .stat-num {
        font-family: 'Playfair Display', serif;
        font-size: 2.25rem;
        font-weight: 700;
        color: #3a2a2e;
        line-height: 1;
        margin: 0 0 0.5rem 0;
    }
    .stat-label {
        font-size: 0.85rem;
        color: #8a6a72;
        margin: 0;
    }

    /* White Section Cards */
    .profile-content-card {
        background: #ffffff;
        border: 1.5px solid #fbd5df;
        border-radius: 20px;
        padding: 1.75rem 2rem;
        box-shadow: 0 4px 16px rgba(212, 77, 110, 0.03);
        margin-bottom: 1.75rem;
    }
    .card-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.35rem;
    }
    .card-heading-title {
        font-family: 'Inter', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0;
    }
    .btn-edit-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        border: 1.5px solid #d4b8c0;
        border-radius: 50px;
        background: #ffffff;
        color: #5a3a42;
        font-family: 'Inter', sans-serif;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 6px 18px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-edit-pill:hover {
        border-color: #d44d6e;
        color: #d44d6e;
        background: #fffbfa;
    }
    .link-view-all-pink {
        color: #d44d6e;
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: color 0.2s;
    }
    .link-view-all-pink:hover {
        color: #b83a58;
    }

    /* Data Profil 3 Columns */
    .profile-info-cols {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
    .info-col-item {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }
    .info-col-label {
        font-size: 0.78rem;
        color: #8a6a72;
    }
    .info-col-val {
        font-size: 0.92rem;
        font-weight: 700;
        color: #3a2a2e;
    }

    /* Pesanan Terbaru Rows */
    .recent-orders-list {
        display: flex;
        flex-direction: column;
    }
    .recent-order-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #fae6ec;
    }
    .recent-order-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .order-row-left {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    .order-row-id {
        font-size: 0.78rem;
        color: #8a6a72;
    }
    .order-row-name {
        font-size: 0.92rem;
        font-weight: 700;
        color: #3a2a2e;
    }
    .order-row-right {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
    .order-status-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 5px 14px;
        border-radius: 50px;
    }
    .order-status-badge.shipping {
        background: #fdf2f5;
        color: #d44d6e;
    }
    .order-status-badge.completed {
        background: #ecfdf5;
        color: #10b981;
    }
    .order-status-badge.pending {
        background: #fef9c3;
        color: #b45309;
    }
    .order-row-price {
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        color: #3a2a2e;
        min-width: 100px;
        text-align: right;
    }
    .btn-lacak-mini {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-family: 'Inter', sans-serif;
        font-size: 0.75rem;
        font-weight: 700;
        color: #d44d6e;
        background: #ffffff;
        border: 1.5px solid #d44d6e;
        border-radius: 50px;
        padding: 4px 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        white-space: nowrap;
    }
    .btn-lacak-mini:hover {
        background: #d44d6e;
        color: #ffffff;
    }

    /* ===== TAB: PESANAN SAYA ===== */
    .my-orders-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    .my-order-card {
        background: #ffffff;
        border: 1.5px solid #fbd5df;
        border-radius: 20px;
        padding: 1.5rem 1.75rem;
        box-shadow: 0 4px 16px rgba(212, 77, 110, 0.03);
    }
    .my-order-meta-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 1rem;
        border-bottom: 1px solid #fae6ec;
        margin-bottom: 1.25rem;
    }
    .order-meta-left {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.85rem;
    }
    .order-meta-date {
        color: #3a2a2e;
        font-weight: 600;
    }
    .order-meta-invoice {
        color: #8a6a72;
        font-weight: 700;
    }
    .my-order-product-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
    }
    .order-product-left {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
    .order-product-img {
        width: 72px;
        height: 72px;
        border-radius: 12px;
        overflow: hidden;
        border: 1.5px solid #fbd5df;
        background: #faf6f7;
        flex-shrink: 0;
    }
    .order-product-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .order-product-title {
        font-family: 'Inter', sans-serif;
        font-size: 0.98rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0 0 0.35rem 0;
    }
    .order-product-variant {
        font-size: 0.82rem;
        color: #8a6a72;
        margin: 0;
    }
    .order-product-price {
        font-family: 'Inter', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: #3a2a2e;
    }
    .my-order-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 1rem;
        border-top: 1px solid #fae6ec;
    }
    .order-total-text {
        font-size: 0.88rem;
        color: #5a3a42;
    }
    .order-total-text strong {
        color: #d44d6e;
        font-size: 1.05rem;
    }
    .btn-cancel-order {
        background: #ffffff;
        border: 1.5px solid #e06b88;
        color: #d44d6e;
        font-family: 'Inter', sans-serif;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 7px 20px;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-cancel-order:hover {
        background: #fdf2f5;
        border-color: #d44d6e;
    }
    .btn-lacak-order {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: #d44d6e;
        border: none;
        color: #ffffff;
        font-family: 'Inter', sans-serif;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 7px 22px;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        box-shadow: 0 3px 12px rgba(212, 77, 110, 0.25);
    }
    .btn-lacak-order:hover {
        background: #b83a58;
        box-shadow: 0 4px 16px rgba(212, 77, 110, 0.35);
    }
    .btn-lacak-order svg {
        width: 14px;
        height: 14px;
    }

    /* ===== EDIT PROFILE MODAL ===== */
    .edit-profile-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(58, 42, 46, 0.45);
        backdrop-filter: blur(4px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }
    .edit-profile-modal-overlay.open {
        display: flex;
    }
    .edit-modal-card {
        background: #ffffff;
        border-radius: 24px;
        max-width: 480px;
        width: 100%;
        padding: 2.25rem 2rem;
        box-shadow: 0 16px 40px rgba(0,0,0,0.18);
        animation: scaleUpModal 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }
    .modal-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.75rem;
    }
    .modal-header-title {
        font-family: 'Inter', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0;
    }
    .modal-close-btn {
        background: none;
        border: none;
        color: #8a6a72;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s;
    }
    .modal-close-btn:hover {
        color: #d44d6e;
    }

    /* Avatar with Camera Badge */
    .modal-avatar-wrapper {
        position: relative;
        width: 80px;
        height: 80px;
        margin: 0 auto 1.75rem;
    }
    .modal-avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid #fbd5df;
        background: #fdf2f5;
    }
    .modal-avatar-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .avatar-camera-badge {
        position: absolute;
        bottom: -2px;
        right: -2px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #ffffff;
        border: 1.5px solid #d4b8c0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3a2a2e;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        transition: all 0.2s ease;
    }
    .avatar-camera-badge:hover {
        border-color: #d44d6e;
        color: #d44d6e;
        transform: scale(1.1);
    }

    /* Modal Form Fields */
    .modal-form-group {
        margin-bottom: 1rem;
    }
    .modal-form-group label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #5a3a42;
        margin-bottom: 0.35rem;
    }
    .modal-input {
        width: 100%;
        height: 44px;
        padding: 0 1rem;
        border: 1.5px solid #e8d0d6;
        border-radius: 10px;
        background: #ffffff;
        font-family: 'Inter', sans-serif;
        font-size: 0.88rem;
        color: #3a2a2e;
        outline: none;
        transition: border-color 0.2s;
        box-sizing: border-box;
    }
    .modal-input:focus {
        border-color: #d44d6e;
    }
    .modal-row-2col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.85rem;
    }
    .modal-btn-row {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        margin-top: 1.75rem;
    }
    .btn-modal-cancel {
        flex: 1;
        height: 44px;
        border: 1.5px solid #d4b8c0;
        background: #ffffff;
        color: #5a3a42;
        border-radius: 50px;
        font-family: 'Inter', sans-serif;
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-modal-cancel:hover {
        border-color: #3a2a2e;
    }
    .btn-modal-save {
        flex: 1.4;
        height: 44px;
        border: none;
        background: #e06b88;
        color: #ffffff;
        border-radius: 50px;
        font-family: 'Inter', sans-serif;
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        box-shadow: 0 3px 12px rgba(224, 107, 136, 0.3);
    }
    .btn-modal-save:hover {
        background: #d44d6e;
    }

    /* Toast Notification */
    .profile-toast {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: #3a2a2e;
        color: #ffffff;
        padding: 0.85rem 1.4rem;
        border-radius: 12px;
        font-size: 0.88rem;
        box-shadow: 0 8px 30px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        gap: 0.65rem;
        z-index: 1001;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        pointer-events: none;
    }
    .profile-toast.show {
        transform: translateY(0);
        opacity: 1;
        pointer-events: auto;
    }
    .profile-toast svg {
        color: #10b981;
    }

    /* ===== ADDRESS LIST STYLES ===== */
    .address-card-item {
        padding: 1.25rem 0;
        border-bottom: 1px solid #fae6ec;
        transition: all 0.2s ease;
    }
    .address-card-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .address-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.45rem;
    }
    .address-label-badge {
        font-weight: 700;
        color: #3a2a2e;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
    }
    .address-primary-tag {
        background: #fce7ee;
        color: #d44d6e;
        padding: 2px 10px;
        border-radius: 50px;
        font-size: 0.72rem;
        font-weight: 700;
    }
    .address-actions {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }
    .address-action-btn {
        background: none;
        border: none;
        color: #d44d6e;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        transition: color 0.2s;
        padding: 0;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    .address-action-btn:hover {
        color: #b83a58;
        text-decoration: underline;
    }
    .address-action-btn.delete {
        color: #9ca3af;
    }
    .address-action-btn.delete:hover {
        color: #f43f5e;
    }
    .address-recipient {
        margin: 0 0 0.35rem 0;
        font-size: 0.9rem;
        color: #3a2a2e;
        font-weight: 600;
    }
    .address-detail-text {
        margin: 0;
        font-size: 0.85rem;
        color: #7a5f67;
        line-height: 1.55;
    }

    /* ===== AVATAR GALLERY MODAL ===== */
    .avatar-picker-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(58, 42, 46, 0.55);
        backdrop-filter: blur(5px);
        z-index: 1150;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }
    .avatar-picker-modal-overlay.open {
        display: flex;
    }
    .avatar-picker-card {
        background: #ffffff;
        border-radius: 28px;
        max-width: 540px;
        width: 100%;
        padding: 2rem 2.25rem;
        box-shadow: 0 20px 50px rgba(58, 42, 46, 0.25);
        animation: scaleUpModal 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        max-height: 90vh;
        overflow-y: auto;
    }
    .avatar-picker-tabs {
        display: flex;
        gap: 0.5rem;
        background: #fdf0f4;
        padding: 5px;
        border-radius: 14px;
        margin-bottom: 1.5rem;
    }
    .avatar-picker-tab-btn {
        flex: 1;
        padding: 9px 12px;
        border: none;
        background: transparent;
        font-family: 'Inter', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        color: #7a5a62;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
    }
    .avatar-picker-tab-btn.active {
        background: #ffffff;
        color: #d44d6e;
        box-shadow: 0 2px 8px rgba(212, 77, 110, 0.12);
    }
    .avatar-preview-spotlight {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 1.5rem;
        padding: 1.25rem 1rem;
        background: #fff8fa;
        border-radius: 20px;
        border: 1.5px dashed #fbd5df;
    }
    .avatar-preview-spotlight-img {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        border: 3.5px solid #d44d6e;
        object-fit: cover;
        box-shadow: 0 6px 18px rgba(212, 77, 110, 0.25);
        background: #fff;
    }
    .avatar-preview-spotlight-badge {
        margin-top: 0.55rem;
        font-size: 0.78rem;
        font-weight: 700;
        color: #d44d6e;
        background: #fdf0f4;
        padding: 3px 14px;
        border-radius: 50px;
        border: 1px solid #fbd5df;
    }
    .avatar-grid-selection {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .avatar-grid-item {
        background: #ffffff;
        border: 2px solid #fed7e2;
        border-radius: 16px;
        padding: 8px 6px;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        transition: all 0.2s ease;
    }
    .avatar-grid-item:hover {
        border-color: #f48da8;
        transform: translateY(-2px);
        background: #fff8fa;
    }
    .avatar-grid-item.selected {
        border-color: #d44d6e;
        background: #fdf0f4;
        box-shadow: 0 0 0 3px rgba(212, 77, 110, 0.2);
        transform: translateY(-2px);
    }
    .avatar-grid-item img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        display: block;
    }
    .avatar-grid-item span {
        font-size: 0.72rem;
        font-weight: 600;
        color: #5a3a42;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }
    .avatar-grid-item.selected span {
        color: #d44d6e;
        font-weight: 700;
    }
    .device-upload-zone {
        border: 2px dashed #f48da8;
        border-radius: 20px;
        padding: 2.25rem 1.5rem;
        text-align: center;
        cursor: pointer;
        background: #fffbfa;
        transition: all 0.2s ease;
        margin-bottom: 1.5rem;
    }
    .device-upload-zone:hover {
        background: #fff0f4;
        border-color: #d44d6e;
        transform: translateY(-2px);
    }
    .device-upload-zone i {
        color: #d44d6e;
        margin-bottom: 0.5rem;
    }
    .device-upload-zone h4 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #3a2a2e;
        margin-bottom: 0.35rem;
    }
    .device-upload-zone p {
        font-size: 0.8rem;
        color: #8a6a72;
        margin: 0;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 900px) {
        .profile-layout-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        .stats-cards-row {
            grid-template-columns: 1fr;
        }
        .profile-info-cols {
            grid-template-columns: 1fr !important;
            gap: 1rem;
        }
    }
</style>

<div class="profile-page-wrapper">
    {{-- Header --}}
    <div class="profile-header">
        <span class="profile-eyebrow">MY SWEET DREAM</span>
        <h1 id="page-user-greeting">Selamat datang, {{ explode(' ', $user['name'])[0] }}</h1>
        <p>Kelola profil, pantau pesanan, dan temukan kembali koleksi favoritmu.</p>
    </div>

    {{-- Layout Grid --}}
    <div class="profile-layout-grid">
        
        {{-- Left: Sidebar --}}
        <aside class="profile-sidebar-card">
            <div class="sidebar-avatar-container">
                <div class="sidebar-avatar-box" id="btn-sidebar-avatar-click" title="Klik untuk ganti avatar">
                    <img id="sidebar-avatar-img" src="{{ asset($user['avatar']) }}" alt="{{ $user['name'] }}">
                    <div class="sidebar-avatar-overlay">
                        <i data-lucide="camera" style="width:20px;height:20px;color:#fff;"></i>
                    </div>
                </div>
                <button type="button" class="sidebar-avatar-badge-btn" id="btn-open-avatar-picker-sidebar" title="Ganti Avatar">
                    <i data-lucide="camera" style="width:14px;height:14px;"></i>
                </button>
            </div>
            <h3 class="sidebar-user-name" id="sidebar-user-name">{{ $user['name'] }}</h3>
            <p class="sidebar-user-email" id="sidebar-user-email">{{ $user['email'] }}</p>

            <nav class="profile-nav-menu">
                <button class="profile-nav-item active" data-tab="ringkasan">
                    <i data-lucide="layout-grid"></i>
                    <span>Ringkasan</span>
                </button>
                <button class="profile-nav-item" data-tab="pesanan">
                    <i data-lucide="shopping-bag"></i>
                    <span>Pesanan saya</span>
                </button>
                <button class="profile-nav-item" data-tab="alamat">
                    <i data-lucide="map-pin"></i>
                    <span>Alamat</span>
                </button>
                <button class="profile-nav-item" data-tab="wishlist">
                    <i data-lucide="heart"></i>
                    <span>Wishlist</span>
                </button>
                <button class="profile-nav-item" data-tab="pengaturan">
                    <i data-lucide="settings"></i>
                    <span>Pengaturan</span>
                </button>
                <button type="button" class="profile-nav-logout-btn" id="btn-profile-logout">
                    <i data-lucide="log-out"></i>
                    <span>Keluar</span>
                </button>
            </nav>
        </aside>

        {{-- Right: Tab Panels --}}
        <main class="profile-content-area">
            
            {{-- 1. TAB RINGKASAN --}}
            <div class="profile-tab-panel active" id="tab-panel-ringkasan">
                {{-- Stat Cards --}}
                <div class="stats-cards-row">
                    <div class="stat-card">
                        <div class="stat-num">{{ $stats['total_orders'] }}</div>
                        <p class="stat-label">Total pesanan</p>
                    </div>
                    <div class="stat-card highlight">
                        <div class="stat-num">{{ $stats['in_delivery'] }}</div>
                        <p class="stat-label">Dalam pengiriman</p>
                    </div>
                    <div class="stat-card">
                        <div class="stat-num">{{ $stats['wishlist_count'] }}</div>
                        <p class="stat-label">Wishlist</p>
                    </div>
                </div>

                {{-- Data Profil Card --}}
                <div class="profile-content-card">
                    <div class="card-header-row">
                        <h2 class="card-heading-title">Data profil</h2>
                        <button class="btn-edit-pill" id="btn-open-edit-modal">Edit profil</button>
                    </div>
                    <div class="profile-info-cols" style="grid-template-columns: repeat(4, 1fr);">
                        <div class="info-col-item">
                            <span class="info-col-label">Nama lengkap</span>
                            <span class="info-col-val" id="disp-user-name">{{ $user['name'] }}</span>
                        </div>
                        <div class="info-col-item">
                            <span class="info-col-label">Email</span>
                            <span class="info-col-val" id="disp-user-email">{{ $user['email'] }}</span>
                        </div>
                        <div class="info-col-item">
                            <span class="info-col-label">Nomor telepon</span>
                            <span class="info-col-val" id="disp-user-phone">{{ $user['phone'] }}</span>
                        </div>
                        <div class="info-col-item">
                            <span class="info-col-label">Tanggal lahir</span>
                            <span class="info-col-val" id="disp-user-birthdate">{{ $user['birthdate'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- Pesanan Terbaru Card --}}
                <div class="profile-content-card">
                    <div class="card-header-row">
                        <h2 class="card-heading-title">Pesanan terbaru</h2>
                        <span class="link-view-all-pink" id="link-goto-myorders">Lihat semua</span>
                    </div>
                    <div class="recent-orders-list">
                        @foreach($recentOrders as $ro)
                            <div class="recent-order-row">
                                <div class="order-row-left">
                                    <span class="order-row-id">{{ $ro['id'] }}</span>
                                    <span class="order-row-name">{{ $ro['title'] }}</span>
                                </div>
                                <div class="order-row-right">
                                    <span class="order-status-badge {{ $ro['status_type'] }}">{{ $ro['status'] }}</span>
                                    <span class="order-row-price">{{ $ro['price'] }}</span>
                                    <a href="/pesanan/{{ $ro['slug'] }}" class="btn-lacak-mini">Lacak</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 2. TAB PESANAN SAYA --}}
            <div class="profile-tab-panel" id="tab-panel-pesanan">
                <div class="my-orders-list">
                    @foreach($myOrders as $idx => $order)
                        <div class="my-order-card" id="my-order-{{ $idx + 1 }}">
                            <div class="my-order-meta-header">
                                <div class="order-meta-left">
                                    <span class="order-meta-date">{{ $order['date'] }}</span>
                                    <span class="order-meta-invoice">{{ $order['id'] }}</span>
                                </div>
                                <span class="order-status-badge {{ $order['status_type'] }}">{{ $order['status'] }}</span>
                            </div>

                            <div class="my-order-product-row">
                                <div class="order-product-left">
                                    <div class="order-product-img">
                                        <img src="{{ asset($order['image']) }}" alt="{{ $order['title'] }}">
                                    </div>
                                    <div>
                                        <h4 class="order-product-title">{{ $order['title'] }}</h4>
                                        <p class="order-product-variant">{{ $order['variant'] }} &bull; Jumlah: {{ $order['qty'] }}</p>
                                    </div>
                                </div>
                                <div class="order-product-price">{{ $order['price'] }}</div>
                            </div>

                            <div class="my-order-footer">
                                <span class="order-total-text">
                                    Total Belanja (incl. ongkir): <strong>{{ $order['total'] }}</strong>
                                </span>
                                <div style="display:flex; align-items:center; gap:0.75rem;">
                                    <a href="/pesanan/{{ $order['slug'] }}" class="btn-lacak-order">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                        Lacak Pesanan
                                    </a>
                                    <button class="btn-cancel-order" onclick="if(confirm('Batalkan pesanan ini?')) { document.getElementById('my-order-{{ $idx + 1 }}').style.opacity = '0.5'; this.textContent = 'Dibatalkan'; this.disabled = true; }">
                                        Batalkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 3. TAB ALAMAT --}}
            <div class="profile-tab-panel" id="tab-panel-alamat">
                <div class="profile-content-card">
                    <div class="card-header-row">
                        <h2 class="card-heading-title">Daftar Alamat Pengiriman</h2>
                        <button class="btn-edit-pill" id="btn-add-new-address">+ Tambah Alamat</button>
                    </div>
                    <div id="address-list-container">
                        @foreach($addresses as $addr)
                            <div class="address-card-item">
                                <div class="address-card-header">
                                    <div class="address-label-badge">
                                        <span>{{ $addr['label'] }}</span>
                                        @if($addr['is_primary']) <span class="address-primary-tag">Utama</span> @endif
                                    </div>
                                    <div class="address-actions">
                                        <button type="button" class="address-action-btn btn-edit-address" data-id="addr-default-{{ $loop->index + 1 }}">
                                            <i data-lucide="edit-3" style="width:14px;height:14px;"></i>
                                            <span>Ubah</span>
                                        </button>
                                    </div>
                                </div>
                                <p class="address-recipient">{{ $addr['name'] }} <span style="font-weight:400;color:#8a6a72;">({{ $addr['phone'] }})</span></p>
                                <p class="address-detail-text">{{ $addr['address'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 4. TAB WISHLIST --}}
                       <div class="profile-tab-panel" id="tab-panel-wishlist">
                <div class="profile-content-card">
                    <div class="card-header-row">
                        <h2 class="card-heading-title">Wishlist Saya ({{ count($wishlistItems) }})</h2>
                        <a href="/katalog" class="link-view-all-pink">Lihat Katalog</a>
                    </div>
                    <p style="font-size:0.9rem; color:#8a6a72; margin:0 0 1.5rem 0;">Simpan busana tidur favorit Anda untuk dibeli kapan saja.</p>
                    @if(count($wishlistItems) === 0)
                        <p style="font-size:0.9rem; color:#8a6a72;">Belum ada produk di wishlist kamu.</p>
                    @else
                        <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem;">
                            @foreach($wishlistItems as $item)
                                <div style="display:flex; gap:1rem; padding:1rem; border:1px solid #fbd5df; border-radius:14px; align-items:center;">
                                    <img src="{{ asset($item['image']) }}" style="width:64px; height:64px; border-radius:10px; object-fit:cover;" alt="{{ $item['title'] }}">
                                    <div style="flex:1;">
                                        <h4 style="margin:0 0 0.25rem; font-size:0.92rem; color:#3a2a2e;">{{ $item['title'] }}</h4>
                                        <span style="font-size:0.88rem; font-weight:700; color:#d44d6e;">{{ $item['price'] }}</span>
                                    </div>
                                    <a href="/produk/{{ $item['slug'] }}" class="btn-edit-pill">Lihat</a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- 5. TAB PENGATURAN --}}
            <div class="profile-tab-panel" id="tab-panel-pengaturan">
                <div class="profile-content-card">
                    <h2 class="card-heading-title" style="margin-bottom:1.5rem;">Pengaturan Akun</h2>
                    <div style="display:flex; flex-direction:column; gap:1.25rem;">
                        {{-- Pengaturan Avatar & Foto Profil --}}
                        <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:1rem; border-bottom:1px solid #fae6ec;">
                            <div style="display:flex; align-items:center; gap:1rem;">
                                <div style="width:52px; height:52px; border-radius:50%; overflow:hidden; border:2px solid #fbd5df; flex-shrink:0; background:#fdf2f5; cursor:pointer;" id="btn-setting-avatar-click" title="Ganti Avatar">
                                    <img id="settings-avatar-preview" src="{{ asset($user['avatar']) }}" alt="Avatar" style="width:100%; height:100%; object-fit:cover; display:block;">
                                </div>
                                <div>
                                    <strong style="display:block; font-size:0.92rem; color:#3a2a2e;">Foto Profil & Avatar</strong>
                                    <span style="font-size:0.8rem; color:#8a6a72;">Pilih avatar karakter manis atau pilih foto dari galeri perangkat</span>
                                </div>
                            </div>
                            <button class="btn-edit-pill" id="btn-setting-change-avatar">
                                <i data-lucide="image" style="width:14px;height:14px;display:inline-block;vertical-align:middle;margin-right:4px;"></i>
                                Ganti Avatar
                            </button>
                        </div>

                        <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:1rem; border-bottom:1px solid #fae6ec;">
                            <div>
                                <strong style="display:block; font-size:0.9rem; color:#3a2a2e;">Pengaturan Alamat Pengiriman</strong>
                                <span style="font-size:0.8rem; color:#8a6a72;">Kelola daftar alamat utama, rumah, kantor, atau lokasi pengiriman lainnya</span>
                            </div>
                            <button class="btn-edit-pill" id="btn-goto-address-settings">Kelola Alamat</button>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:1rem; border-bottom:1px solid #fae6ec;">
                            <div>
                                <strong style="display:block; font-size:0.9rem; color:#3a2a2e;">Notifikasi Email & Promo</strong>
                                <span style="font-size:0.8rem; color:#8a6a72;">Dapatkan kabar diskon eksklusif dan status pesanan</span>
                            </div>
                            <input type="checkbox" checked style="accent-color:#d44d6e; width:18px; height:18px;">
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:1rem; border-bottom:1px solid #fae6ec;">
                            <div>
                                <strong style="display:block; font-size:0.9rem; color:#3a2a2e;">Keamanan Akun & Profil</strong>
                                <span style="font-size:0.8rem; color:#8a6a72;">Perbarui informasi profil atau nama akun Anda</span>
                            </div>
                            <button class="btn-edit-pill" id="btn-setting-edit-profile">Ubah Profil</button>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <div>
                                <strong style="display:block; font-size:0.9rem; color:#f43f5e;">Keluar dari Akun</strong>
                                <span style="font-size:0.8rem; color:#8a6a72;">Akhiri sesi Anda pada perangkat ini</span>
                            </div>
                            <button class="btn-edit-pill" style="color:#f43f5e; border-color:#fca5a5;" id="btn-setting-logout">Logout</button>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

{{-- 1. MODAL UBAH DATA PROFIL --}}
<div class="edit-profile-modal-overlay" id="edit-profile-modal">
    <div class="edit-modal-card">
        <div class="modal-header-row">
            <h3 class="modal-header-title">Ubah Data Profil</h3>
            <button class="modal-close-btn" id="btn-close-edit-modal" aria-label="Tutup Modal">
                <i data-lucide="x" style="width:20px;height:20px;"></i>
            </button>
        </div>

        {{-- Avatar with Camera Icon --}}
        <div class="modal-avatar-wrapper" style="cursor:pointer;" id="btn-edit-modal-avatar-wrapper" title="Ganti Avatar">
            <div class="modal-avatar-img">
                <img id="modal-avatar-preview" src="{{ asset($user['avatar']) }}" alt="Avatar">
            </div>
            <div class="avatar-camera-badge" id="btn-edit-modal-avatar-badge" title="Ganti Foto">
                <i data-lucide="camera" style="width:14px;height:14px;"></i>
            </div>
        </div>

        {{-- Form Fields --}}
        <form id="edit-profile-form" onsubmit="event.preventDefault();">
            <div class="modal-form-group">
                <label for="edit-input-nama">Nama Lengkap</label>
                <input type="text" id="edit-input-nama" class="modal-input" placeholder="Nama lengkap Anda" required>
            </div>

            <div class="modal-form-group">
                <label for="edit-input-email">Alamat Email</label>
                <input type="email" id="edit-input-email" class="modal-input" placeholder="email@domain.com" required>
            </div>

            <div class="modal-row-2col">
                <div class="modal-form-group">
                    <label for="edit-input-phone">Nomor Telepon</label>
                    <input type="tel" id="edit-input-phone" class="modal-input" placeholder="08xxxxxxxxxx">
                </div>
                <div class="modal-form-group">
                    <label for="edit-input-birthdate">Tanggal Lahir</label>
                    <input type="date" id="edit-input-birthdate" class="modal-input">
                </div>
            </div>

            <div class="modal-form-group">
                <label for="edit-input-city">Kota Domisili</label>
                <input type="text" id="edit-input-city" class="modal-input" placeholder="Contoh: Jakarta Selatan">
            </div>

            <div class="modal-btn-row">
                <button type="button" class="btn-modal-cancel" id="btn-cancel-edit">Batal</button>
                <button type="submit" class="btn-modal-save" id="btn-save-profile">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- 2. MODAL UBAH & TAMBAH ALAMAT PENGIRIMAN --}}
<div class="edit-profile-modal-overlay" id="edit-address-modal">
    <div class="edit-modal-card" style="max-width: 520px;">
        <div class="modal-header-row">
            <h3 class="modal-header-title" id="address-modal-title">Ubah Alamat Pengiriman</h3>
            <button class="modal-close-btn" id="btn-close-address-modal" aria-label="Tutup Modal">
                <i data-lucide="x" style="width:20px;height:20px;"></i>
            </button>
        </div>

        <form id="edit-address-form" onsubmit="event.preventDefault();">
            <input type="hidden" id="address-edit-id" value="">

            <div class="modal-form-group">
                <label for="address-input-label">Label Alamat <span style="color:#8a6a72;font-weight:400;">(Contoh: Rumah, Kantor, Kos)</span></label>
                <input type="text" id="address-input-label" class="modal-input" placeholder="Misal: Alamat Utama atau Rumah" required>
            </div>

            <div class="modal-row-2col">
                <div class="modal-form-group">
                    <label for="address-input-name">Nama Penerima</label>
                    <input type="text" id="address-input-name" class="modal-input" placeholder="Nama penerima paket" required>
                </div>
                <div class="modal-form-group">
                    <label for="address-input-phone">Nomor Telepon</label>
                    <input type="tel" id="address-input-phone" class="modal-input" placeholder="08xxxxxxxxxx" required>
                </div>
            </div>

            <div class="modal-form-group">
                <label for="address-input-address">Alamat Lengkap <span style="color:#f43f5e;">*</span></label>
                <textarea id="address-input-address" class="modal-input" rows="3" style="height:auto; min-height:80px; padding:0.75rem 1rem; resize:vertical; font-family:'Inter',sans-serif; line-height:1.45;" placeholder="Nama jalan, nomor rumah/gedung, RT/RW, kelurahan, kecamatan" required></textarea>
            </div>

            <div class="modal-row-2col">
                <div class="modal-form-group">
                    <label for="address-input-city">Kota / Kabupaten</label>
                    <input type="text" id="address-input-city" class="modal-input" placeholder="Kota atau Kabupaten" required>
                </div>
                <div class="modal-form-group">
                    <label for="address-input-province">Provinsi</label>
                    <input type="text" id="address-input-province" class="modal-input" placeholder="Provinsi" required>
                </div>
            </div>

            <div class="modal-form-group">
                <label for="address-input-postal">Kode Pos</label>
                <input type="text" id="address-input-postal" class="modal-input" placeholder="Kode pos 5 digit" style="max-width: 220px;" required>
            </div>

            <div class="modal-form-group" style="margin-top: 0.5rem;">
                <label style="display:flex; align-items:center; gap:0.6rem; cursor:pointer; font-weight:500; font-size:0.85rem; color:#3a2a2e;">
                    <input type="checkbox" id="address-input-primary" style="accent-color:#d44d6e; width:17px; height:17px;">
                    <span>Jadikan sebagai Alamat Utama pengiriman</span>
                </label>
            </div>

            <div class="modal-btn-row">
                <button type="button" class="btn-modal-cancel" id="btn-cancel-address">Batal</button>
                <button type="submit" class="btn-modal-save" id="btn-save-address">Simpan Alamat</button>
            </div>
        </form>
    </div>
</div>

{{-- 3. MODAL GANTI FOTO PROFIL / AVATAR --}}
<div class="avatar-picker-modal-overlay" id="avatar-picker-modal">
    <div class="avatar-picker-card">
        <div class="modal-header-row" style="margin-bottom: 1.25rem;">
            <div>
                <h3 class="modal-header-title">Pilih Foto Profil / Avatar</h3>
                <p style="font-size:0.8rem; color:#8a6a72; margin-top:3px; margin-bottom:0;">Pilih avatar karakter eksklusif atau unggah foto dari galeri perangkatmu</p>
            </div>
            <button class="modal-close-btn" id="btn-close-avatar-modal" aria-label="Tutup Modal">
                <i data-lucide="x" style="width:20px;height:20px;"></i>
            </button>
        </div>

        {{-- Spotlight Active Preview --}}
        <div class="avatar-preview-spotlight">
            <img id="avatar-spotlight-img" class="avatar-preview-spotlight-img" src="{{ asset($user['avatar']) }}" alt="Preview">
            <span class="avatar-preview-spotlight-badge" id="avatar-spotlight-badge">Avatar Saat Ini</span>
            <input type="hidden" id="active-selected-avatar-val" value="{{ $user['avatar'] }}">
        </div>

        {{-- Tab Switchers (Galeri Avatar vs Unggah dari Galeri Perangkat) --}}
        <div class="avatar-picker-tabs">
            <button type="button" class="avatar-picker-tab-btn active" id="tab-btn-avatar-gallery">
                <i data-lucide="sparkles" style="width:15px;height:15px;"></i>
                <span>Galeri Avatar</span>
            </button>
            <button type="button" class="avatar-picker-tab-btn" id="tab-btn-device-gallery">
                <i data-lucide="image" style="width:15px;height:15px;"></i>
                <span>Pilih dari Galeri Foto</span>
            </button>
        </div>

        {{-- View 1: Preset Avatar Gallery --}}
        <div id="view-avatar-preset-gallery">
            <div class="avatar-grid-selection">
                <button type="button" class="avatar-grid-item" data-path="images/avatars/avatar-1.svg" data-name="Sweet Bunny" title="Sweet Bunny">
                    <img src="{{ asset('images/avatars/avatar-1.svg') }}" alt="Sweet Bunny">
                    <span>Bunny</span>
                </button>
                <button type="button" class="avatar-grid-item" data-path="images/avatars/avatar-2.svg" data-name="Dreamy Cat" title="Dreamy Cat">
                    <img src="{{ asset('images/avatars/avatar-2.svg') }}" alt="Dreamy Cat">
                    <span>Cat</span>
                </button>
                <button type="button" class="avatar-grid-item" data-path="images/avatars/avatar-3.svg" data-name="Cloud Princess" title="Cloud Princess">
                    <img src="{{ asset('images/avatars/avatar-3.svg') }}" alt="Cloud Princess">
                    <span>Cloud</span>
                </button>
                <button type="button" class="avatar-grid-item" data-path="images/avatars/avatar-4.svg" data-name="Teddy Slumber" title="Teddy Slumber">
                    <img src="{{ asset('images/avatars/avatar-4.svg') }}" alt="Teddy Slumber">
                    <span>Teddy</span>
                </button>
                <button type="button" class="avatar-grid-item" data-path="images/avatars/avatar-5.svg" data-name="Velvet Swan" title="Velvet Swan">
                    <img src="{{ asset('images/avatars/avatar-5.svg') }}" alt="Velvet Swan">
                    <span>Swan</span>
                </button>
                <button type="button" class="avatar-grid-item" data-path="images/avatars/avatar-6.svg" data-name="Moon Dreamer" title="Moon Dreamer">
                    <img src="{{ asset('images/avatars/avatar-6.svg') }}" alt="Moon Dreamer">
                    <span>Moon</span>
                </button>
                <button type="button" class="avatar-grid-item" data-path="images/avatars/avatar-7.svg" data-name="Pastel Girl" title="Pastel Girl">
                    <img src="{{ asset('images/avatars/avatar-7.svg') }}" alt="Pastel Girl">
                    <span>Girl</span>
                </button>
                <button type="button" class="avatar-grid-item" data-path="images/avatars/avatar-8.svg" data-name="Silk Panda" title="Silk Panda">
                    <img src="{{ asset('images/avatars/avatar-8.svg') }}" alt="Silk Panda">
                    <span>Panda</span>
                </button>
                <button type="button" class="avatar-grid-item" data-path="images/alya-avatar.jpg" data-name="Alya Classic" title="Alya Classic">
                    <img src="{{ asset('images/alya-avatar.jpg') }}" alt="Alya Classic">
                    <span>Alya</span>
                </button>
            </div>
        </div>

        {{-- View 2: Upload / Pilih dari Galeri Perangkat --}}
        <div id="view-avatar-device-gallery" style="display: none;">
            <div class="device-upload-zone" id="btn-trigger-file-input">
                <i data-lucide="upload-cloud" style="width:36px;height:36px;display:block;margin:0 auto 0.6rem;"></i>
                <h4>Pilih Gambar dari Galeri Perangkat</h4>
                <p>Klik untuk memilih foto dari galeri foto di HP atau Komputer Anda (JPG, PNG, WEBP)</p>
                <input type="file" id="input-device-photo" accept="image/*" style="display: none;">
            </div>
            <div id="device-upload-status" style="display:none; text-align:center; margin-bottom:1rem; font-size:0.84rem; color:#10b981; font-weight:600;">
                <i data-lucide="check" style="width:16px;height:16px;display:inline-block;vertical-align:middle;margin-right:4px;"></i>
                Foto dari galeri siap disimpan!
            </div>
        </div>

        <div class="modal-btn-row">
            <button type="button" class="btn-modal-cancel" id="btn-cancel-avatar-modal">Batal</button>
            <button type="button" class="btn-modal-save" id="btn-save-avatar-modal">Simpan Foto Profil</button>
        </div>
    </div>
</div>

{{-- 4. MODAL KONFIRMASI LOGOUT --}}
<div class="edit-profile-modal-overlay" id="modal-logout-confirm" style="z-index: 1200;">
    <div class="edit-modal-card" style="max-width: 400px; text-align: center; padding: 2.25rem 2rem;">
        <div style="width: 58px; height: 58px; border-radius: 50%; background: #fff1f2; color: #f43f5e; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; border: 2px solid #fed7e2;">
            <i data-lucide="log-out" style="width: 28px; height: 28px;"></i>
        </div>
        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 700; color: #3a2a2e; margin: 0 0 0.5rem 0;">Keluar dari Akun?</h3>
        <p style="font-size: 0.88rem; color: #8a6a72; line-height: 1.5; margin: 0 0 1.75rem 0;">Apakah Anda yakin ingin keluar dan mengakhiri sesi akun Sweet Dreams pada perangkat ini?</p>
        
        <div style="display: flex; gap: 0.85rem; justify-content: center;">
            <button type="button" class="btn-modal-cancel" id="btn-cancel-logout-modal" style="flex: 1;">Batal</button>
            <button type="button" class="btn-modal-save" id="btn-confirm-do-logout" style="flex: 1.2; background: #f43f5e; box-shadow: 0 3px 12px rgba(244, 63, 94, 0.3);">Ya, Keluar</button>
        </div>
    </div>
</div>

{{-- TOAST NOTIFICATION --}}
<div class="profile-toast" id="profile-toast">
    <i data-lucide="check-circle" style="width:20px;height:20px;"></i>
    <span id="profile-toast-msg">Perubahan berhasil disimpan!</span>
</div>

<script>
window.initialAddresses = @json($addresses);
document.addEventListener('DOMContentLoaded', function() {
    // Re-init Lucide Icons
    lucide.createIcons();

    // Restore active tab from sessionStorage (e.g. when returning from tracking page)
    const savedTab = sessionStorage.getItem('sweetdreams_active_tab');
    if (savedTab) {
        sessionStorage.removeItem('sweetdreams_active_tab');
        // Will call switchTab after it's defined below
        window._pendingTab = savedTab;
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function showProfileToast(message) {
        const toast = document.getElementById('profile-toast');
        const toastMsg = document.getElementById('profile-toast-msg');
        if (!toast) return;
        if (toastMsg) toastMsg.textContent = message;
        toast.classList.add('show');
        if (window.profileToastTimeout) clearTimeout(window.profileToastTimeout);
        window.profileToastTimeout = setTimeout(() => {
            toast.classList.remove('show');
        }, 3200);
    }

    // 1. Sidebar Tab Switching
    const navItems = document.querySelectorAll('.profile-nav-item');
    const tabPanels = document.querySelectorAll('.profile-tab-panel');

    function switchTab(targetTab) {
        navItems.forEach(item => {
            if (item.getAttribute('data-tab') === targetTab) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });

        tabPanels.forEach(panel => {
            if (panel.id === 'tab-panel-' + targetTab) {
                panel.classList.add('active');
            } else {
                panel.classList.remove('active');
            }
        });
    }

    navItems.forEach(item => {
        item.addEventListener('click', function() {
            const target = this.getAttribute('data-tab');
            if (target) switchTab(target);
        });
    });

    // Link 'Lihat semua' in recent orders -> switches to 'pesanan'
    const linkGotoOrders = document.getElementById('link-goto-myorders');
    if (linkGotoOrders) {
        linkGotoOrders.addEventListener('click', function() {
            switchTab('pesanan');
        });
    }

    // Shortcut from Tab Pengaturan -> switches to 'alamat'
    const btnGotoAddrSettings = document.getElementById('btn-goto-address-settings');
    if (btnGotoAddrSettings) {
        btnGotoAddrSettings.addEventListener('click', function() {
            switchTab('alamat');
        });
    }

    // 2. Edit Profile Modal Handling
    const profileModal = document.getElementById('edit-profile-modal');
    const btnOpenProfileModal = document.getElementById('btn-open-edit-modal');
    const btnSettingProfileModal = document.getElementById('btn-setting-edit-profile');
    const btnCloseProfileModal = document.getElementById('btn-close-edit-modal');
    const btnCancelProfile = document.getElementById('btn-cancel-edit');
    const formProfile = document.getElementById('edit-profile-form');

    function openProfileModal() {
        const user = window.SweetDreamsAuth ? window.SweetDreamsAuth.getCurrentUser() : null;
        if (user) {
            document.getElementById('edit-input-nama').value = user.name || '';
            document.getElementById('edit-input-email').value = user.email || '';
            document.getElementById('edit-input-phone').value = user.phone || '';
            document.getElementById('edit-input-birthdate').value = user.birthdate || '';
            document.getElementById('edit-input-city').value = user.city || '';
        } else {
            document.getElementById('edit-input-nama').value = '{{ $user['name'] }}';
            document.getElementById('edit-input-email').value = '{{ $user['email'] }}';
            document.getElementById('edit-input-phone').value = '{{ $user['phone'] }}';
            document.getElementById('edit-input-birthdate').value = '{{ $user['birthdate_raw'] }}';
            document.getElementById('edit-input-city').value = '{{ $user['city'] }}';
        }
        profileModal.classList.add('open');
    }

    function closeProfileModal() {
        profileModal.classList.remove('open');
    }

    if (btnOpenProfileModal) btnOpenProfileModal.addEventListener('click', openProfileModal);
    if (btnSettingProfileModal) btnSettingProfileModal.addEventListener('click', openProfileModal);
    if (btnCloseProfileModal) btnCloseProfileModal.addEventListener('click', closeProfileModal);
    if (btnCancelProfile) btnCancelProfile.addEventListener('click', closeProfileModal);

        if (formProfile) {
        formProfile.addEventListener('submit', function() {
            const newName = document.getElementById('edit-input-nama').value.trim();
            const newEmail = document.getElementById('edit-input-email').value.trim();
            const newPhone = document.getElementById('edit-input-phone').value.trim();
            const newBirthdate = document.getElementById('edit-input-birthdate').value;
            const newCity = document.getElementById('edit-input-city').value.trim();

            if (!newName) {
                alert('Nama lengkap tidak boleh kosong.');
                return;
            }

            fetch('/api/profile', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    name: newName, email: newEmail, phone: newPhone,
                    birthdate: newBirthdate, city: newCity
                })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                if (status === 200) {
                    showProfileToast('Perubahan data profil berhasil disimpan!');
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    const msg = body.message || (body.errors ? Object.values(body.errors)[0][0] : 'Gagal menyimpan perubahan.');
                    alert(msg);
                }
            })
            .catch(() => alert('Tidak bisa menghubungi server, coba lagi.'));
        });
    }

    // 3. Edit & Add Address Modal Handling
    const addressModal = document.getElementById('edit-address-modal');
    const btnCloseAddressModal = document.getElementById('btn-close-address-modal');
    const btnCancelAddress = document.getElementById('btn-cancel-address');
    const btnAddNewAddress = document.getElementById('btn-add-new-address');
    const formAddress = document.getElementById('edit-address-form');

    function closeAddressModal() {
        addressModal.classList.remove('open');
    }

    if (btnCloseAddressModal) btnCloseAddressModal.addEventListener('click', closeAddressModal);
    if (btnCancelAddress) btnCancelAddress.addEventListener('click', closeAddressModal);

       function openAddAddressModal() {
        document.getElementById('address-modal-title').textContent = 'Tambah Alamat Pengiriman';
        document.getElementById('address-edit-id').value = '';
        document.getElementById('address-input-label').value = 'Rumah';
        document.getElementById('address-input-name').value = '{{ $user['name'] }}';
        document.getElementById('address-input-phone').value = '{{ $user['phone'] === 'Belum diisi' ? '' : $user['phone'] }}';
        document.getElementById('address-input-address').value = '';
        document.getElementById('address-input-city').value = '{{ $user['city'] === 'Belum diisi' ? '' : $user['city'] }}';
        document.getElementById('address-input-province').value = '';
        document.getElementById('address-input-postal').value = '';
        document.getElementById('address-input-primary').checked = false;

        addressModal.classList.add('open');
        document.getElementById('address-input-address').focus();
    }

    if (btnAddNewAddress) btnAddNewAddress.addEventListener('click', openAddAddressModal);

    function openEditAddressModal(addrId) {
                const addresses = window.initialAddresses || [];
        const addr = addresses.find(a => String(a.id) === String(addrId));
        if (!addr) {
            openAddAddressModal();
            return;
        }

        document.getElementById('address-modal-title').textContent = 'Ubah Alamat Pengiriman';
        document.getElementById('address-edit-id').value = addr.id;
        document.getElementById('address-input-label').value = addr.label || 'Alamat';
        document.getElementById('address-input-name').value = addr.name || '';
        document.getElementById('address-input-phone').value = addr.phone || '';
        document.getElementById('address-input-address').value = addr.address || '';
        document.getElementById('address-input-city').value = addr.city || '';
        document.getElementById('address-input-province').value = addr.province || '';
        document.getElementById('address-input-postal').value = addr.postal_code || '';
        document.getElementById('address-input-primary').checked = !!addr.is_primary;

        addressModal.classList.add('open');
        document.getElementById('address-input-address').focus();
    }

        if (formAddress) {
        formAddress.addEventListener('submit', function() {
            const addrId = document.getElementById('address-edit-id').value.trim();
            const label = document.getElementById('address-input-label').value.trim();
            const name = document.getElementById('address-input-name').value.trim();
            const phone = document.getElementById('address-input-phone').value.trim();
            const address = document.getElementById('address-input-address').value.trim();
            const city = document.getElementById('address-input-city').value.trim();
            const province = document.getElementById('address-input-province').value.trim();
            const postal_code = document.getElementById('address-input-postal').value.trim();
            const is_primary = document.getElementById('address-input-primary').checked;

            if (!name) {
                alert('Nama penerima wajib diisi.');
                return;
            }
            if (!address) {
                alert('Kolom alamat lengkap wajib diisi.');
                return;
            }

            const addrData = {
                label: label || 'Alamat',
                recipient_name: name,
                phone: phone,
                address: address,
                city: city,
                province: province,
                postal_code: postal_code,
                is_primary: is_primary
            };

            const url = addrId ? `/api/addresses/${addrId}` : '/api/addresses';
            const method = addrId ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(addrData)
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                if (status === 200 || status === 201) {
                    closeAddressModal();
                    showProfileToast(addrId ? 'Alamat pengiriman berhasil diubah!' : 'Alamat baru berhasil ditambahkan!');
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    const msg = body.message || (body.errors ? Object.values(body.errors)[0][0] : 'Gagal menyimpan alamat.');
                    alert(msg);
                }
            })
            .catch(() => alert('Tidak bisa menghubungi server, coba lagi.'));
        });
    }

    // 4. Render Address List Dynamically
    function renderAddressList() {
        const container = document.getElementById('address-list-container');
        if (!container) return;

               const addresses = window.initialAddresses || [];

        if (addresses.length === 0) {
            container.innerHTML = `
                <div style="text-align:center; padding: 2.5rem 1rem; color: #8a6a72;">
                    <p style="margin-bottom:1rem; font-size:0.92rem;">Belum ada alamat pengiriman yang tersimpan.</p>
                    <button type="button" class="btn-edit-pill" id="btn-add-address-empty">+ Tambah Alamat Pengiriman</button>
                </div>
            `;
            document.getElementById('btn-add-address-empty')?.addEventListener('click', openAddAddressModal);
            return;
        }

        let html = '';
        addresses.forEach(addr => {
            const locDetails = [addr.city, addr.province, addr.postal_code].filter(Boolean).join(', ');
            html += `
                <div class="address-card-item" data-id="${addr.id}">
                    <div class="address-card-header">
                        <div class="address-label-badge">
                            <span>${escapeHtml(addr.label || 'Alamat')}</span>
                            ${addr.is_primary ? '<span class="address-primary-tag">Utama</span>' : ''}
                        </div>
                        <div class="address-actions">
                            ${!addr.is_primary ? `<button type="button" class="address-action-btn btn-set-primary" data-id="${addr.id}">Jadikan Utama</button>` : ''}
                            <button type="button" class="address-action-btn btn-edit-addr-item" data-id="${addr.id}">
                                <i data-lucide="edit-3" style="width:14px;height:14px;"></i>
                                <span>Ubah</span>
                            </button>
                            ${addresses.length > 1 ? `
                            <button type="button" class="address-action-btn delete btn-delete-addr-item" data-id="${addr.id}" title="Hapus Alamat">
                                <i data-lucide="trash-2" style="width:14px;height:14px;"></i>
                                <span>Hapus</span>
                            </button>` : ''}
                        </div>
                    </div>
                    <p class="address-recipient">${escapeHtml(addr.name)} <span style="font-weight:400;color:#8a6a72;">(${escapeHtml(addr.phone || '-')})</span></p>
                    <p class="address-detail-text">
                        ${escapeHtml(addr.address || 'Alamat belum diatur')}${locDetails ? '<br><small style="color:#8a6a72;">' + escapeHtml(locDetails) + '</small>' : ''}
                    </p>
                </div>
            `;
        });

        container.innerHTML = html;
        lucide.createIcons();

        // Bind address buttons
        container.querySelectorAll('.btn-edit-addr-item').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                openEditAddressModal(id);
            });
        });

             container.querySelectorAll('.btn-set-primary').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                fetch(`/api/addresses/${id}/primary`, {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(res => res.json().then(data => ({ status: res.status, body: data })))
                .then(({ status, body }) => {
                    if (status === 200) {
                        showProfileToast('Alamat utama berhasil diubah!');
                        setTimeout(() => window.location.reload(), 600);
                    } else {
                        alert(body.message || 'Gagal mengubah alamat utama.');
                    }
                })
                .catch(() => alert('Tidak bisa menghubungi server, coba lagi.'));
            });
        });

                container.querySelectorAll('.btn-delete-addr-item').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                if (!confirm('Apakah Anda yakin ingin menghapus alamat pengiriman ini?')) return;

                fetch(`/api/addresses/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(res => res.json().then(data => ({ status: res.status, body: data })))
                .then(({ status, body }) => {
                    if (status === 200) {
                        showProfileToast('Alamat berhasil dihapus.');
                        setTimeout(() => window.location.reload(), 600);
                    } else {
                        alert(body.message || 'Gagal menghapus alamat.');
                    }
                })
                .catch(() => alert('Tidak bisa menghubungi server, coba lagi.'));
            });
        });
    }

    // 5. Sync Active User from SweetDreamsAuth
    function syncActiveUserData() {
        if (!window.SweetDreamsAuth) return;
        const activeUser = window.SweetDreamsAuth.getCurrentUser();
        // if (activeUser) {
        //     const firstName = (activeUser.name || 'Pengguna').split(' ')[0];
        //     const greetingEl = document.getElementById('page-user-greeting');
        //     const sideNameEl = document.getElementById('sidebar-user-name');
        //     const sideEmailEl = document.getElementById('sidebar-user-email');
        //     const dispNameEl = document.getElementById('disp-user-name');
        //     const dispEmailEl = document.getElementById('disp-user-email');
        //     const dispPhoneEl = document.getElementById('disp-user-phone');
        //     const dispBirthEl = document.getElementById('disp-user-birthdate');

        //     if (greetingEl) greetingEl.textContent = 'Selamat datang, ' + firstName;
        //     if (sideNameEl) sideNameEl.textContent = activeUser.name;
        //     if (sideEmailEl) sideEmailEl.textContent = activeUser.email;
        //     if (dispNameEl) dispNameEl.textContent = activeUser.name;
        //     if (dispEmailEl) dispEmailEl.textContent = activeUser.email;
        //     if (dispPhoneEl) dispPhoneEl.textContent = activeUser.phone || '-';
        //     if (dispBirthEl) dispBirthEl.textContent = activeUser.birthdate || '-';

        //     // Sync Avatars Across All Profile Containers
        //     if (activeUser.avatar) {
        //         const avatarSrc = activeUser.avatar.startsWith('data:') || activeUser.avatar.startsWith('http') || activeUser.avatar.startsWith('/') 
        //             ? activeUser.avatar 
        //             : '/' + activeUser.avatar;

        //         const sideImg = document.getElementById('sidebar-avatar-img');
        //         const modalImg = document.getElementById('modal-avatar-preview');
        //         const setImg = document.getElementById('settings-avatar-preview');
        //         const spotImg = document.getElementById('avatar-spotlight-img');

        //         if (sideImg) sideImg.src = avatarSrc;
        //         if (modalImg) modalImg.src = avatarSrc;
        //         if (setImg) setImg.src = avatarSrc;
        //         if (spotImg) spotImg.src = avatarSrc;
        //     }

        //     // Admin badge
        //     if (activeUser.role === 'admin') {
        //         const badge = document.createElement('span');
        //         badge.textContent = 'ADMIN';
        //         badge.style.cssText = 'background:#d44d6e;color:#fff;font-size:0.65rem;font-weight:700;padding:2px 8px;border-radius:10px;margin-left:6px;vertical-align:middle;';
        //         if (sideNameEl) sideNameEl.appendChild(badge);
        //     }
        // }

        renderAddressList();
    }

    // ===== AVATAR PICKER MODAL LOGIC =====
    const avatarModal = document.getElementById('avatar-picker-modal');
    const btnCloseAvatarModal = document.getElementById('btn-close-avatar-modal');
    const btnCancelAvatarModal = document.getElementById('btn-cancel-avatar-modal');
    const btnSaveAvatarModal = document.getElementById('btn-save-avatar-modal');

    const btnOpenSidebarAvatar = document.getElementById('btn-sidebar-avatar-click');
    const btnOpenSidebarBadge = document.getElementById('btn-open-avatar-picker-sidebar');
    const btnSettingAvatarClick = document.getElementById('btn-setting-avatar-click');
    const btnSettingChangeAvatar = document.getElementById('btn-setting-change-avatar');
    const btnModalAvatarClick = document.getElementById('btn-edit-modal-avatar-wrapper');
    const btnModalAvatarBadge = document.getElementById('btn-edit-modal-avatar-badge');

    const spotlightImg = document.getElementById('avatar-spotlight-img');
    const spotlightBadge = document.getElementById('avatar-spotlight-badge');
    const activeAvatarInput = document.getElementById('active-selected-avatar-val');

    const tabBtnPreset = document.getElementById('tab-btn-avatar-gallery');
    const tabBtnDevice = document.getElementById('tab-btn-device-gallery');
    const viewPreset = document.getElementById('view-avatar-preset-gallery');
    const viewDevice = document.getElementById('view-avatar-device-gallery');

    const btnTriggerUpload = document.getElementById('btn-trigger-file-input');
    const inputDevicePhoto = document.getElementById('input-device-photo');
    const uploadStatus = document.getElementById('device-upload-status');

    let currentSelectedAvatar = '';

    function openAvatarModal() {
        const user = window.SweetDreamsAuth ? window.SweetDreamsAuth.getCurrentUser() : null;
        const currentAvatar = (user && user.avatar) ? user.avatar : '{{ $user["avatar"] }}';
        currentSelectedAvatar = currentAvatar;

        const avatarSrc = currentAvatar.startsWith('data:') || currentAvatar.startsWith('http') || currentAvatar.startsWith('/') 
            ? currentAvatar 
            : '/' + currentAvatar;

        if (spotlightImg) spotlightImg.src = avatarSrc;
        if (activeAvatarInput) activeAvatarInput.value = currentAvatar;
        if (spotlightBadge) spotlightBadge.textContent = 'Avatar Saat Ini';

        // Select matching item in preset grid if matches
        let foundMatch = false;
        document.querySelectorAll('.avatar-grid-item').forEach(item => {
            const path = item.getAttribute('data-path');
            if (path === currentAvatar) {
                item.classList.add('selected');
                if (spotlightBadge) spotlightBadge.textContent = item.getAttribute('data-name');
                foundMatch = true;
            } else {
                item.classList.remove('selected');
            }
        });

        if (!foundMatch && currentAvatar.startsWith('data:')) {
            if (spotlightBadge) spotlightBadge.textContent = 'Foto Galeri Perangkat';
        }

        switchAvatarTab('preset');
        avatarModal.classList.add('open');
    }

    function closeAvatarModal() {
        avatarModal.classList.remove('open');
    }

    function switchAvatarTab(tab) {
        if (tab === 'preset') {
            if (tabBtnPreset) tabBtnPreset.classList.add('active');
            if (tabBtnDevice) tabBtnDevice.classList.remove('active');
            if (viewPreset) viewPreset.style.display = 'block';
            if (viewDevice) viewDevice.style.display = 'none';
        } else {
            if (tabBtnDevice) tabBtnDevice.classList.add('active');
            if (tabBtnPreset) tabBtnPreset.classList.remove('active');
            if (viewPreset) viewPreset.style.display = 'none';
            if (viewDevice) viewDevice.style.display = 'block';
        }
    }

    if (tabBtnPreset) tabBtnPreset.addEventListener('click', () => switchAvatarTab('preset'));
    if (tabBtnDevice) tabBtnDevice.addEventListener('click', () => switchAvatarTab('device'));

    // Open triggers
    if (btnOpenSidebarAvatar) btnOpenSidebarAvatar.addEventListener('click', openAvatarModal);
    if (btnOpenSidebarBadge) btnOpenSidebarBadge.addEventListener('click', openAvatarModal);
    if (btnSettingAvatarClick) btnSettingAvatarClick.addEventListener('click', openAvatarModal);
    if (btnSettingChangeAvatar) btnSettingChangeAvatar.addEventListener('click', openAvatarModal);
    if (btnModalAvatarClick) btnModalAvatarClick.addEventListener('click', openAvatarModal);
    if (btnModalAvatarBadge) btnModalAvatarBadge.addEventListener('click', openAvatarModal);

    // Close triggers
    if (btnCloseAvatarModal) btnCloseAvatarModal.addEventListener('click', closeAvatarModal);
    if (btnCancelAvatarModal) btnCancelAvatarModal.addEventListener('click', closeAvatarModal);

    // Preset Grid item click
    document.querySelectorAll('.avatar-grid-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.avatar-grid-item').forEach(i => i.classList.remove('selected'));
            this.classList.add('selected');

            const path = this.getAttribute('data-path');
            const name = this.getAttribute('data-name');
            currentSelectedAvatar = path;

            if (spotlightImg) spotlightImg.src = '/' + path;
            if (spotlightBadge) spotlightBadge.textContent = name;
            if (activeAvatarInput) activeAvatarInput.value = path;
            if (uploadStatus) uploadStatus.style.display = 'none';
        });
    });

    // Upload from device gallery
    if (btnTriggerUpload && inputDevicePhoto) {
        btnTriggerUpload.addEventListener('click', () => {
            inputDevicePhoto.click();
        });

        inputDevicePhoto.addEventListener('change', function(e) {
            const file = e.target.files && e.target.files[0];
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                alert('Silakan pilih file gambar (JPG, PNG, atau WEBP).');
                return;
            }

            // Max 4MB
            if (file.size > 4 * 1024 * 1024) {
                alert('Ukuran foto maksimal 4MB.');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(evt) {
                const base64Data = evt.target.result;
                currentSelectedAvatar = base64Data;

                if (spotlightImg) spotlightImg.src = base64Data;
                if (spotlightBadge) spotlightBadge.textContent = 'Foto Galeri Perangkat';
                if (activeAvatarInput) activeAvatarInput.value = base64Data;

                document.querySelectorAll('.avatar-grid-item').forEach(i => i.classList.remove('selected'));
                if (uploadStatus) uploadStatus.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
    }

    // Save avatar choice permanently
    if (btnSaveAvatarModal) {
        btnSaveAvatarModal.addEventListener('click', function() {
            if (!currentSelectedAvatar) {
                closeAvatarModal();
                return;
            }

            if (window.SweetDreamsAuth) {
                window.SweetDreamsAuth.updateUserProfile({
                    avatar: currentSelectedAvatar
                });
            }

            syncActiveUserData();
            closeAvatarModal();
            showProfileToast('Foto profil baru berhasil disimpan!');
        });
    }

    // Initial sync
    syncActiveUserData();
    window.addEventListener('sweetdreams_user_updated', syncActiveUserData);

    // Restore saved tab if returning from tracking page
    if (window._pendingTab) {
        switchTab(window._pendingTab);
        delete window._pendingTab;
    }

   

    // 7. Logout Handlers with Custom Confirmation Modal
    const logoutModal = document.getElementById('modal-logout-confirm');
    const btnCancelLogout = document.getElementById('btn-cancel-logout-modal');
    const btnConfirmLogout = document.getElementById('btn-confirm-do-logout');
    const btnSidebarLogout = document.getElementById('btn-profile-logout');
    const btnSettingLogout = document.getElementById('btn-setting-logout');

    function openLogoutModal(e) {
        if (e) e.preventDefault();
        if (logoutModal) logoutModal.classList.add('open');
    }

    function closeLogoutModal() {
        if (logoutModal) logoutModal.classList.remove('open');
    }

       function executeLogout() {
        window.location.href = '/logout';
    }

    if (btnSidebarLogout) btnSidebarLogout.addEventListener('click', openLogoutModal);
    if (btnSettingLogout) btnSettingLogout.addEventListener('click', openLogoutModal);
    if (btnCancelLogout) btnCancelLogout.addEventListener('click', closeLogoutModal);
    if (btnConfirmLogout) btnConfirmLogout.addEventListener('click', executeLogout);

    // Also close on background click
    if (logoutModal) {
        logoutModal.addEventListener('click', function(e) {
            if (e.target === logoutModal) closeLogoutModal();
        });
    }
});
</script>
@endsection
