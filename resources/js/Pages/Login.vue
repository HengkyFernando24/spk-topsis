<template>
    <div class="auth-root">

        <!-- Lightweight static background (no heavy animation) -->
        <div class="bg-canvas">
            <div class="bg-grid"></div>
            <div class="bg-accent-1"></div>
            <div class="bg-accent-2"></div>
        </div>

        <!-- Main Card -->
        <div class="auth-card" :class="{ 'card-shake': shaking }">

            <!-- Left Panel -->
            <div class="brand-panel">
                <div class="brand-inner">
                    <div class="brand-badge">
                        <span class="material-symbols-outlined">school</span>
                    </div>
                    <p class="brand-eyebrow">Sistem Penunjang Keputusan</p>
                    <h1 class="brand-title">SPK<span>TOPSIS</span></h1>
                    <p class="brand-desc">Platform berbasis data untuk mendukung pengambilan keputusan institusi secara akurat dan efisien</p>

                    <div class="feature-grid">
                        <div class="feature-card">
                            <span class="material-symbols-outlined">groups</span>
                            <span class="feature-label">Siswa Aktif</span>
                        </div>
                        <div class="feature-card">
                            <span class="material-symbols-outlined">menu_book</span>
                            <span class="feature-label">Mata Pelajaran</span>
                        </div>
                        <div class="feature-card">
                            <span class="material-symbols-outlined">bar_chart</span>
                            <span class="feature-label">Analisis Data</span>
                        </div>
                        <div class="feature-card">
                            <span class="material-symbols-outlined">verified</span>
                            <span class="feature-label">Sistem Andal</span>
                        </div>
                        <div class="feature-card">
                            <span class="material-symbols-outlined">leaderboard</span>
                            <span class="feature-label">Peringkat TOPSIS</span>
                        </div>
                        <div class="feature-card">
                            <span class="material-symbols-outlined">shield_lock</span>
                            <span class="feature-label">Data Aman</span>
                        </div>
                    </div>
                </div>

                <div class="brand-footer">
                    <span class="material-symbols-outlined">shield_lock</span>
                    Terproteksi dengan Enkripsi Tingkat Institusi
                </div>
            </div>

            <!-- Right Panel — Form -->
            <div class="form-panel">
                <div class="form-header">
                    <div class="form-logo-sm">
                        <span class="material-symbols-outlined">school</span>
                    </div>
                    <h2>Autentikasi Administrator</h2>
                    <p>Masukkan kredensial resmi untuk mengakses sistem</p>
                </div>

                <transition name="slide-down">
                    <div v-if="errorMessage" class="error-alert">
                        <span class="material-symbols-outlined">error_outline</span>
                        <span>{{ errorMessage }}</span>
                    </div>
                </transition>

                <div class="form-body">
                    <div class="field-group" :class="{ focused: focusedField === 'name', filled: form.name }">
                        <label>Nama Administrator</label>
                        <div class="field-wrap">
                            <span class="field-icon material-symbols-outlined">manage_accounts</span>
                            <input
                                v-model="form.name"
                                type="text"
                                placeholder="Masukkan nama lengkap administrator"
                                autofocus
                                autocomplete="username"
                                @focus="focusedField = 'name'"
                                @blur="focusedField = ''"
                                @keydown.enter="handleLogin"
                            />
                            <span v-if="form.name" class="field-check material-symbols-outlined">check_circle</span>
                        </div>
                    </div>

                    <div class="field-group" :class="{ focused: focusedField === 'password', filled: form.password }">
                        <label>Kata Sandi</label>
                        <div class="field-wrap">
                            <span class="field-icon material-symbols-outlined">key</span>
                            <input
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                placeholder="••••••••••••"
                                autocomplete="current-password"
                                @focus="focusedField = 'password'"
                                @blur="focusedField = ''"
                                @keydown.enter="handleLogin"
                            />
                            <button type="button" class="field-toggle" @click="showPassword = !showPassword">
                                <span class="material-symbols-outlined">{{ showPassword ? 'visibility_off' : 'visibility' }}</span>
                            </button>
                        </div>
                    </div>

                    <div class="form-meta">
                        <label class="remember-label">
                            <input type="checkbox" v-model="form.remember" />
                            <span class="checkbox-custom">
                                <span v-if="form.remember" class="material-symbols-outlined">check</span>
                            </span>
                            Pertahankan Sesi Login
                        </label>
                        <a href="#" class="forgot-link">Pemulihan Kata Sandi</a>
                    </div>

                    <button
                        type="button"
                        class="submit-btn"
                        :disabled="!form.name || !form.password || isLoading"
                        @click="handleLogin"
                    >
                        <span v-if="!isLoading" class="material-symbols-outlined">login</span>
                        <span v-if="!isLoading">Masuk ke Sistem</span>

                        <!-- Loading state -->
                        <span v-if="isLoading" class="btn-spinner material-symbols-outlined">progress_activity</span>
                        <span v-if="isLoading">Memverifikasi...</span>

                        <span v-if="!isLoading" class="btn-arrow material-symbols-outlined">arrow_forward</span>
                    </button>

                    <p class="enter-hint">
                        <span class="material-symbols-outlined">keyboard_return</span>
                        Tekan <kbd>Enter</kbd> untuk masuk
                    </p>
                </div>

                <div class="form-footer">
                    <span class="material-symbols-outlined">corporate_fare</span>
                    &copy; {{ new Date().getFullYear() }} SPK TOPSIS. Seluruh hak cipta dilindungi undang-undang.
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const form        = ref({ name: '', password: '', remember: false });
const errorMessage = ref('');
const showPassword = ref(false);
const focusedField = ref('');
const shaking      = ref(false);
const isLoading    = ref(false);

// Cache CSRF token so we don't re-fetch it on every login attempt
let csrfReady = false;

const primeCsrf = async () => {
    if (csrfReady) return;
    try {
        await axios.get('/sanctum/csrf-cookie');
        csrfReady = true;
    } catch {
        // Will retry on actual login attempt
    }
};

// Kick off CSRF fetch immediately on page load (background, non-blocking)
primeCsrf();

const triggerShake = () => {
    shaking.value = true;
    setTimeout(() => (shaking.value = false), 550);
};

const handleLogin = async () => {
    if (!form.value.name || !form.value.password || isLoading.value) return;

    errorMessage.value = '';
    isLoading.value    = true;

    try {
        // CSRF already fetched in background; only await if somehow missed
        if (!csrfReady) {
            await axios.get('/sanctum/csrf-cookie');
            csrfReady = true;
        }

        await axios.post('/login', {
            name:     form.value.name,
            password: form.value.password,
            remember: form.value.remember,
        });

        // Instant redirect — no extra await
        window.location.replace('/dashboard');

    } catch (error) {
        isLoading.value = false;

        if (error.response?.status === 422 || error.response?.status === 401) {
            errorMessage.value = 'Kredensial tidak valid. Periksa kembali nama dan kata sandi Anda.';
        } else {
            errorMessage.value = 'Gangguan pada server. Silakan coba beberapa saat lagi.';
        }
        triggerShake();
    }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200');

/* ── PALETTE
   Primary dark green  : #1a6b3c
   Mid green           : #21854a
   Light green         : #2a9d5c
   Tint bg             : #f2f9f5
──────────────────── */

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.auth-root {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f2f9f5;
    font-family: Arial, sans-serif;
    position: relative;
    overflow: hidden;
    padding: 1.5rem;
}

/* ════════════════════════════════
   LIGHTWEIGHT STATIC BACKGROUND
   — replaced heavy CSS animations
════════════════════════════════ */
.bg-canvas {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 0;
}

/* Subtle grid — static, zero GPU cost */
.bg-grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(26,107,60,0.06) 1px, transparent 1px),
        linear-gradient(90deg, rgba(26,107,60,0.06) 1px, transparent 1px);
    background-size: 64px 64px;
}

/* Two static radial accents — replaces all blob/wave/sweep/diamond animations */
.bg-accent-1 {
    position: absolute;
    top: -120px;
    left: -120px;
    width: 480px;
    height: 400px;
    background: radial-gradient(ellipse, rgba(26,107,60,0.12) 0%, transparent 70%);
    border-radius: 50%;
}
.bg-accent-2 {
    position: absolute;
    bottom: -100px;
    right: -80px;
    width: 400px;
    height: 360px;
    background: radial-gradient(ellipse, rgba(33,133,74,0.08) 0%, transparent 70%);
    border-radius: 50%;
}

/* ════════════════════════════════
   CARD
════════════════════════════════ */
.auth-card {
    position: relative;
    z-index: 10;
    display: flex;
    width: 100%;
    max-width: 920px;
    min-height: 560px;
    border-radius: 24px;
    overflow: hidden;
    box-shadow:
        0 0 0 1px rgba(26,107,60,0.1),
        0 24px 64px rgba(26,107,60,0.14),
        0 4px 16px rgba(0,0,0,0.05);
    /* Simplified entry — no scale to avoid reflow */
    animation: cardIn 0.4s ease both;
}
@keyframes cardIn {
    from { opacity:0; transform:translateY(16px); }
    to   { opacity:1; transform:translateY(0); }
}
.card-shake {
    animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both !important;
}
@keyframes shake {
    10%,90%     { transform: translateX(-3px); }
    20%,80%     { transform: translateX( 5px); }
    30%,50%,70% { transform: translateX(-5px); }
    40%,60%     { transform: translateX( 5px); }
}

/* ════════════════════════════════
   BRAND PANEL (Left)
════════════════════════════════ */
.brand-panel {
    flex: 0 0 340px;
    background: linear-gradient(148deg, #0e4d2a 0%, #1a6b3c 55%, #21854a 100%);
    padding: 2.5rem 2rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
}
.brand-panel::before {
    content: '';
    position: absolute;
    top: -60px; left: -60px;
    width: 260px; height: 260px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255,255,255,0.07) 0%, transparent 70%);
    pointer-events: none;
}
.brand-panel::after {
    content: '';
    position: absolute;
    bottom: -100px; right: -100px;
    width: 280px; height: 280px;
    border-radius: 50%;
    border: 1.5px solid rgba(255,255,255,0.08);
    pointer-events: none;
}

.brand-inner { position: relative; z-index: 1; }

.brand-badge {
    width: 56px; height: 56px;
    background: rgba(255,255,255,0.12);
    border: 1.5px solid rgba(255,255,255,0.22);
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 1.2rem;
}
.brand-badge .material-symbols-outlined { font-size: 26px; color: #fff; }

.brand-eyebrow {
    font-size: 0.58rem;
    font-weight: 700;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.55);
    margin-bottom: 0.3rem;
}

.brand-title {
    font-size: 2.15rem;
    font-weight: 800;
    color: #fff;
    line-height: 1.08;
    letter-spacing: -0.02em;
    margin-bottom: 0.9rem;
}
.brand-title span { color: #86efac; }

.brand-desc {
    font-size: 0.79rem;
    color: rgba(255,255,255,0.5);
    line-height: 1.7;
    font-weight: 300;
    margin-bottom: 1.75rem;
}

/* Feature grid — simplified, no per-card animations */
.feature-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.55rem;
}
.feature-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.4rem;
    padding: 0.8rem 0.3rem 0.7rem;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 14px;
    cursor: default;
    transition: background 0.2s, border-color 0.2s;
}
.feature-card .material-symbols-outlined {
    font-size: 20px;
    color: rgba(255,255,255,0.7);
    transition: color 0.2s;
}
.feature-card:hover {
    background: rgba(255,255,255,0.13);
    border-color: rgba(134,239,172,0.4);
}
.feature-card:hover .material-symbols-outlined { color: #86efac; }
.feature-label {
    font-size: 0.57rem;
    font-weight: 600;
    letter-spacing: 0.02em;
    color: rgba(255,255,255,0.45);
    text-align: center;
    line-height: 1.3;
}

.brand-footer {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    font-size: 0.67rem;
    color: rgba(255,255,255,0.38);
    font-weight: 400;
    position: relative;
    z-index: 1;
}
.brand-footer .material-symbols-outlined { font-size: 13px; color: #86efac; }

/* ════════════════════════════════
   FORM PANEL (Right)
════════════════════════════════ */
.form-panel {
    flex: 1;
    background: #ffffff;
    padding: 2.5rem 2.75rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.form-header { margin-bottom: 1.6rem; }
.form-logo-sm {
    display: none;
    width: 44px; height: 44px;
    background: linear-gradient(135deg, #1a6b3c, #0e4d2a);
    border-radius: 12px;
    align-items: center; justify-content: center;
    margin-bottom: 1rem;
}
.form-logo-sm .material-symbols-outlined { font-size: 22px; color: #fff; }

.form-header h2 {
    font-size: 1.55rem;
    font-weight: 800;
    color: #0e4d2a;
    margin-bottom: 0.28rem;
    letter-spacing: -0.02em;
}
.form-header p {
    font-size: 0.81rem;
    color: #6b7280;
}

/* Alerts */
.error-alert {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.72rem 1rem;
    border-radius: 12px;
    font-size: 0.77rem;
    font-weight: 500;
    margin-bottom: 1.1rem;
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #b91c1c;
}
.error-alert .material-symbols-outlined { font-size: 17px; flex-shrink: 0; }

.slide-down-enter-active { transition: all 0.25s ease; }
.slide-down-leave-active  { transition: all 0.15s ease; }
.slide-down-enter-from    { opacity:0; transform:translateY(-6px); }
.slide-down-leave-to      { opacity:0; }

/* Fields */
.form-body { display: flex; flex-direction: column; gap: 1rem; }

.field-group { display: flex; flex-direction: column; gap: 0.32rem; }
.field-group label {
    font-size: 0.66rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #9ca3af;
    padding-left: 2px;
    transition: color 0.15s;
}
.field-group.focused label { color: #1a6b3c; }
.field-group.filled  label { color: #0e4d2a; }

.field-wrap { position: relative; display: flex; align-items: center; }

.field-icon {
    position: absolute;
    left: 13px;
    font-size: 18px !important;
    color: #d1d5db;
    pointer-events: none;
    transition: color 0.15s;
}
.field-group.focused .field-icon,
.field-group.filled  .field-icon { color: #1a6b3c; }

.field-wrap input {
    width: 100%;
    background: #f9fafb;
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    padding: 0.84rem 2.8rem 0.84rem 2.9rem;
    font-family: Arial, sans-serif;
    font-size: 0.875rem;
    color: #111827;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
}
.field-wrap input::placeholder { color: #c4c9d1; }
.field-wrap input:focus {
    background: #fff;
    border-color: #1a6b3c;
    box-shadow: 0 0 0 3px rgba(26,107,60,0.1);
}
.field-group.filled .field-wrap input {
    background: #fff;
    border-color: #86efac;
}

.field-check {
    position: absolute;
    right: 13px;
    font-size: 16px !important;
    color: #1a6b3c;
}
.field-toggle {
    position: absolute;
    right: 11px;
    background: none;
    border: none;
    cursor: pointer;
    color: #c4c9d1;
    display: flex;
    align-items: center;
    padding: 4px;
    border-radius: 6px;
    transition: color 0.15s;
}
.field-toggle:hover { color: #1a6b3c; }
.field-toggle .material-symbols-outlined { font-size: 17px !important; }

/* Meta */
.form-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 0.05rem;
}
.remember-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    font-size: 0.77rem;
    color: #6b7280;
    user-select: none;
}
.remember-label input[type="checkbox"] { display: none; }
.checkbox-custom {
    width: 17px; height: 17px;
    border-radius: 5px;
    border: 1.5px solid #d1d5db;
    background: #fff;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.15s;
    flex-shrink: 0;
}
.remember-label:has(input:checked) .checkbox-custom {
    background: #1a6b3c;
    border-color: #1a6b3c;
}
.checkbox-custom .material-symbols-outlined { font-size: 11px !important; color: #fff; }

.forgot-link {
    font-size: 0.74rem;
    font-weight: 600;
    color: #1a6b3c;
    text-decoration: none;
    transition: color 0.15s;
}
.forgot-link:hover { color: #0e4d2a; text-decoration: underline; }

/* Submit */
.submit-btn {
    width: 100%;
    margin-top: 0.2rem;
    padding: 0.93rem 1.5rem;
    background: linear-gradient(135deg, #21854a 0%, #1a6b3c 50%, #0e4d2a 100%);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-family: Arial, sans-serif;
    font-size: 0.81rem;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.55rem;
    transition: transform 0.15s, box-shadow 0.15s, opacity 0.15s;
    box-shadow: 0 4px 16px rgba(26,107,60,0.32);
}
.submit-btn:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 8px 24px rgba(26,107,60,0.4);
}
.submit-btn:active:not(:disabled) {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(26,107,60,0.25);
}
.submit-btn:disabled {
    opacity: 0.42;
    cursor: not-allowed;
    box-shadow: none;
}
.submit-btn .material-symbols-outlined { font-size: 17px !important; }

/* Spinner — single CSS animation, very lightweight */
.btn-spinner {
    animation: spin 0.7s linear infinite;
    font-size: 17px !important;
}
@keyframes spin { to { transform: rotate(360deg); } }

.btn-arrow {
    margin-left: auto;
    opacity: 0.6;
    transition: transform 0.15s, opacity 0.15s;
}
.submit-btn:hover:not(:disabled) .btn-arrow {
    transform: translateX(3px);
    opacity: 1;
}

/* Enter hint */
.enter-hint {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.35rem;
    font-size: 0.69rem;
    color: #9ca3af;
    margin-top: 0.08rem;
}
.enter-hint .material-symbols-outlined { font-size: 12px !important; color: #21854a; }
.enter-hint kbd {
    display: inline-flex;
    align-items: center;
    padding: 1px 6px;
    border-radius: 5px;
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    font-family: Arial, sans-serif;
    font-size: 0.67rem;
    font-weight: 600;
    color: #4b5563;
}

/* Footer */
.form-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    font-size: 0.66rem;
    color: #9ca3af;
    margin-top: 1.4rem;
}
.form-footer .material-symbols-outlined { font-size: 12px !important; }

/* Responsive */
@media (max-width: 720px) {
    .brand-panel  { display: none; }
    .auth-card    { border-radius: 20px; }
    .form-panel   { padding: 2rem 1.5rem; }
    .form-logo-sm { display: flex; }
}

/* Respect user's reduced-motion preference */
@media (prefers-reduced-motion: reduce) {
    .auth-card    { animation: none; }
    .btn-spinner  { animation: none; }
}
</style>