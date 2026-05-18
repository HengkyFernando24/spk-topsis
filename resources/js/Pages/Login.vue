<template>
    <div class="auth-root">
        <!-- Animated Background -->
        <div class="bg-canvas">
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>
            <div class="grid-overlay"></div>
        </div>

        <!-- Floating Decorative Shapes -->
        <div class="shapes">
            <div class="shape shape-hex"></div>
            <div class="shape shape-ring"></div>
            <div class="shape shape-dot-grid"></div>
        </div>

        <!-- Main Card -->
        <div class="auth-card" :class="{ 'card-shake': shaking }">

            <!-- Left Panel — Brand -->
            <div class="brand-panel">
                <div class="brand-glow"></div>

                <div class="brand-top">
                    <div class="brand-badge">
                        <span class="material-symbols-outlined">school</span>
                    </div>
                    <p class="brand-eyebrow">Sistem Penunjang Keputusan</p>
                    <h1 class="brand-title">SPK<span>TOPSIS</span></h1>
                    <p class="brand-desc">Platform penunjang keputusan berbasis data dirancang untuk mendukung pengambilan keputusan institusi secara akurat dan efisien</p>
                </div>

                <div class="brand-stats">
                    <div class="stat">
                        <span class="material-symbols-outlined">groups</span>
                        <div>
                            <strong>2.400+</strong>
                            <small>Siswa Aktif</small>
                        </div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat">
                        <span class="material-symbols-outlined">menu_book</span>
                        <div>
                            <strong>180+</strong>
                            <small>Mata Pelajaran</small>
                        </div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat">
                        <span class="material-symbols-outlined">verified</span>
                        <div>
                            <strong>99.9%</strong>
                            <small>Uptime</small>
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
                    <h2>Autentikasi Administrator</h2>
                    <p>Silakan masukkan kredensial resmi Anda untuk mengakses sistem penunjang keputusan</p>
                </div>

                <!-- Error Alert -->
                <transition name="slide-down">
                    <div v-if="errorMessage" class="error-alert">
                        <span class="material-symbols-outlined">error_outline</span>
                        <span>{{ errorMessage }}</span>
                    </div>
                </transition>

                <!-- Success Alert -->
                <transition name="slide-down">
                    <div v-if="successMessage" class="success-alert">
                        <span class="material-symbols-outlined">check_circle</span>
                        <span>{{ successMessage }}</span>
                    </div>
                </transition>

                <div class="form-body">
                    <!-- Name Field -->
                    <div class="field-group" :class="{ focused: focusedField === 'name', filled: form.name }">
                        <label>Nama Administrator</label>
                        <div class="field-wrap">
                            <span class="field-icon material-symbols-outlined">manage_accounts</span>
                            <input
                                v-model="form.name"
                                type="text"
                                placeholder="Masukkan nama lengkap administrator"
                                autofocus
                                @focus="focusedField = 'name'"
                                @blur="focusedField = ''"
                            />
                            <span v-if="form.name" class="field-check material-symbols-outlined">check_circle</span>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="field-group" :class="{ focused: focusedField === 'password', filled: form.password }">
                        <label>Kata Sandi</label>
                        <div class="field-wrap">
                            <span class="field-icon material-symbols-outlined">key</span>
                            <input
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                placeholder="••••••••••••"
                                @focus="focusedField = 'password'"
                                @blur="focusedField = ''"
                            />
                            <button type="button" class="field-toggle" @click="showPassword = !showPassword">
                                <span class="material-symbols-outlined">{{ showPassword ? 'visibility_off' : 'visibility' }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- Remember & Forgot -->
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

                    <!-- Submit -->
                    <button
                        type="button"
                        class="submit-btn"
                        :class="{ loading: isLoading }"
                        :disabled="isLoading || !form.name || !form.password"
                        @click="handleLogin"
                    >
                        <span v-if="isLoading" class="btn-spinner material-symbols-outlined">progress_activity</span>
                        <span v-else class="material-symbols-outlined">login</span>
                        {{ isLoading ? 'Memverifikasi Identitas...' : 'Masuk ke Sistem' }}
                        <span v-if="!isLoading" class="btn-arrow material-symbols-outlined">arrow_forward</span>
                    </button>
                </div>

                <!-- Footer -->
                <div class="form-footer">
                    <span class="material-symbols-outlined">corporate_fare</span>
                    &copy; {{ new Date().getFullYear() }} SPK TOPSIS. Seluruh hak cipta dilindungi undang-undang.
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const form = ref({ name: '', password: '', remember: false });
const isLoading    = ref(false);
const errorMessage = ref('');
const successMessage = ref('');
const showPassword = ref(false);
const focusedField = ref('');
const shaking      = ref(false);

const triggerShake = () => {
    shaking.value = true;
    setTimeout(() => (shaking.value = false), 600);
};

const handleLogin = async () => {
    if (!form.value.name || !form.value.password) return;
    isLoading.value    = true;
    errorMessage.value = '';
    successMessage.value = '';

    try {
        await axios.get('/sanctum/csrf-cookie');
        await axios.post('/login', form.value);
        successMessage.value = 'Autentikasi berhasil. Anda akan segera diarahkan ke dasbor utama.';
        setTimeout(() => (window.location.href = '/dashboard'), 1200);
    } catch (error) {
        if (error.response?.status === 422 || error.response?.status === 401) {
            errorMessage.value = 'Kredensial yang Anda masukkan tidak valid. Mohon periksa kembali nama dan kata sandi Anda.';
        } else {
            errorMessage.value = 'Terjadi gangguan pada server. Silakan hubungi tim teknis atau coba kembali beberapa saat lagi.';
        }
        triggerShake();
    } finally {
        isLoading.value = false;
    }
};
</script>

<style scoped>
/* ── Google Fonts ── */
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200');

/* ── Reset & Root ── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.auth-root {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #08090d;
    font-family: 'arial', arial;
    position: relative;
    overflow: hidden;
    padding: 1.5rem;
}

/* ── Animated Background ── */
.bg-canvas {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 0;
}

.orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(100px);
    opacity: 0.45;
    animation: drift 12s ease-in-out infinite alternate;
}
.orb-1 {
    width: 520px; height: 520px;
    background: radial-gradient(circle, #059669, transparent 70%);
    top: -160px; left: -120px;
    animation-duration: 14s;
}
.orb-2 {
    width: 420px; height: 420px;
    background: radial-gradient(circle, #0ea5e9, transparent 70%);
    bottom: -140px; right: -100px;
    animation-duration: 18s;
    animation-delay: -6s;
}
.orb-3 {
    width: 300px; height: 300px;
    background: radial-gradient(circle, #7c3aed, transparent 70%);
    top: 40%; left: 50%;
    opacity: 0.2;
    animation-duration: 20s;
    animation-delay: -10s;
}
@keyframes drift {
    from { transform: translate(0, 0) scale(1); }
    to   { transform: translate(40px, 30px) scale(1.08); }
}

.grid-overlay {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
    background-size: 60px 60px;
}

/* ── Decorative Shapes ── */
.shapes { position: fixed; inset: 0; pointer-events: none; z-index: 0; }

.shape-hex {
    position: absolute;
    top: 8%; right: 8%;
    width: 120px; height: 140px;
    background: conic-gradient(from 0deg, #059669 0%, transparent 60%);
    clip-path: polygon(50% 0%,100% 25%,100% 75%,50% 100%,0% 75%,0% 25%);
    opacity: 0.12;
    animation: spin-slow 30s linear infinite;
}
.shape-ring {
    position: absolute;
    bottom: 12%; left: 5%;
    width: 140px; height: 140px;
    border-radius: 50%;
    border: 2px solid rgba(14,165,233,0.2);
    box-shadow: 0 0 40px rgba(14,165,233,0.1);
    animation: pulse-ring 4s ease-in-out infinite;
}
.shape-dot-grid {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 300px; height: 300px;
    background-image: radial-gradient(circle, rgba(255,255,255,0.08) 1px, transparent 1px);
    background-size: 20px 20px;
    opacity: 0.5;
}

@keyframes spin-slow { to { transform: rotate(360deg); } }
@keyframes pulse-ring {
    0%, 100% { transform: scale(1); opacity: 0.5; }
    50%       { transform: scale(1.1); opacity: 1; }
}

/* ── Card ── */
.auth-card {
    position: relative;
    z-index: 10;
    display: flex;
    width: 100%;
    max-width: 900px;
    min-height: 560px;
    border-radius: 28px;
    overflow: hidden;
    box-shadow:
        0 0 0 1px rgba(255,255,255,0.06),
        0 40px 80px rgba(0,0,0,0.6),
        0 0 60px rgba(5,150,105,0.08);
    animation: card-in 0.7s cubic-bezier(0.16,1,0.3,1) both;
}

@keyframes card-in {
    from { opacity: 0; transform: translateY(32px) scale(0.97); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}

.card-shake {
    animation: shake 0.55s cubic-bezier(.36,.07,.19,.97) both !important;
}
@keyframes shake {
    10%,90%  { transform: translateX(-3px); }
    20%,80%  { transform: translateX(5px); }
    30%,50%,70% { transform: translateX(-6px); }
    40%,60%  { transform: translateX(6px); }
}

/* ── Brand Panel (Left) ── */
.brand-panel {
    flex: 0 0 340px;
    background:
        linear-gradient(150deg, #0a1a14 0%, #051208 60%, #071118 100%);
    padding: 2.5rem 2rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    border-right: 1px solid rgba(255,255,255,0.04);
    overflow: hidden;
}

.brand-glow {
    position: absolute;
    top: -60px; left: -60px;
    width: 260px; height: 260px;
    background: radial-gradient(circle, rgba(5,150,105,0.3), transparent 70%);
    pointer-events: none;
}

.brand-badge {
    width: 60px; height: 60px;
    background: linear-gradient(135deg, #059669, #034d33);
    border-radius: 18px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 1.25rem;
    box-shadow: 0 8px 24px rgba(5,150,105,0.4);
    border: 1px solid rgba(5,150,105,0.5);
}
.brand-badge .material-symbols-outlined {
    font-size: 30px;
    color: #fff;
}

.brand-eyebrow {
    font-family: 'arial', sans-serif;
    font-size: 0.65rem;
    font-weight: 600;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: #34d399;
    margin-bottom: 0.4rem;
}

.brand-title {
    font-family: 'arial', sans-serif;
    font-size: 2.1rem;
    font-weight: 800;
    color: #f0fdf4;
    line-height: 1.1;
    margin-bottom: 1rem;
}
.brand-title span { color: #34d399; }

.brand-desc {
    font-size: 0.82rem;
    color: rgba(255,255,255,0.4);
    line-height: 1.65;
    font-weight: 300;
}

/* Stats */
.brand-stats {
    display: flex;
    gap: 0.75rem;
    align-items: center;
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.05);
    border-radius: 16px;
    padding: 1rem 0.75rem;
    margin-top: 2rem;
}
.stat {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.35rem;
    text-align: center;
}
.stat .material-symbols-outlined {
    font-size: 18px;
    color: #34d399;
}
.stat strong {
    display: block;
    font-family: 'arial', sans-serif;
    font-size: 0.9rem;
    font-weight: 800;
    color: #f0fdf4;
}
.stat small {
    font-size: 0.6rem;
    color: rgba(255,255,255,0.35);
    font-weight: 400;
    white-space: nowrap;
}
.stat-divider {
    width: 1px;
    height: 36px;
    background: rgba(255,255,255,0.07);
    flex-shrink: 0;
}

/* Brand Footer */
.brand-footer {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1.5rem;
    font-size: 0.7rem;
    color: rgba(255,255,255,0.3);
    font-weight: 500;
}
.brand-footer .material-symbols-outlined {
    font-size: 14px;
    color: #34d399;
    opacity: 0.7;
}

/* ── Form Panel (Right) ── */
.form-panel {
    flex: 1;
    background: #0f1117;
    padding: 2.5rem 2.75rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.form-header {
    margin-bottom: 1.75rem;
}
.form-header h2 {
    font-family: 'arial', sans-serif;
    font-size: 1.75rem;
    font-weight: 800;
    color: #f8fafc;
    margin-bottom: 0.35rem;
}
.form-header p {
    font-size: 0.82rem;
    color: rgba(255,255,255,0.4);
    font-weight: 300;
}

/* Alerts */
.error-alert, .success-alert {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.75rem 1rem;
    border-radius: 12px;
    font-size: 0.78rem;
    font-weight: 500;
    margin-bottom: 1.25rem;
}
.error-alert {
    background: rgba(239,68,68,0.1);
    border: 1px solid rgba(239,68,68,0.25);
    color: #fca5a5;
}
.success-alert {
    background: rgba(5,150,105,0.12);
    border: 1px solid rgba(5,150,105,0.3);
    color: #6ee7b7;
}
.error-alert .material-symbols-outlined,
.success-alert .material-symbols-outlined { font-size: 18px; flex-shrink: 0; }

.slide-down-enter-active { transition: all 0.3s cubic-bezier(0.16,1,0.3,1); }
.slide-down-leave-active  { transition: all 0.2s ease-in; }
.slide-down-enter-from    { opacity: 0; transform: translateY(-8px); }
.slide-down-leave-to      { opacity: 0; transform: translateY(-4px); }

/* ── Field Group ── */
.form-body { display: flex; flex-direction: column; gap: 1.1rem; }

.field-group {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}
.field-group label {
    font-size: 0.68rem;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.35);
    padding-left: 2px;
    transition: color 0.2s;
}
.field-group.focused label { color: #34d399; }

.field-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

.field-icon {
    position: absolute;
    left: 14px;
    font-size: 18px !important;
    color: rgba(255,255,255,0.2);
    transition: color 0.2s;
    pointer-events: none;
}
.field-group.focused .field-icon { color: #34d399; }

.field-wrap input {
    width: 100%;
    background: rgba(255,255,255,0.04);
    border: 1.5px solid rgba(255,255,255,0.06);
    border-radius: 12px;
    padding: 0.85rem 2.8rem 0.85rem 3rem;
    font-family: 'arial', sans-serif;
    font-size: 0.88rem;
    font-weight: 400;
    color: #f1f5f9;
    outline: none;
    transition: all 0.2s;
    letter-spacing: 0.01em;
}
.field-wrap input::placeholder { color: rgba(255,255,255,0.18); }
.field-wrap input:focus {
    background: rgba(5,150,105,0.06);
    border-color: #059669;
    box-shadow: 0 0 0 3px rgba(5,150,105,0.12);
}

.field-check {
    position: absolute;
    right: 14px;
    font-size: 16px !important;
    color: #34d399;
}
.field-toggle {
    position: absolute;
    right: 12px;
    background: none;
    border: none;
    cursor: pointer;
    color: rgba(255,255,255,0.25);
    display: flex;
    align-items: center;
    padding: 4px;
    transition: color 0.2s;
    border-radius: 6px;
}
.field-toggle:hover { color: #34d399; }
.field-toggle .material-symbols-outlined { font-size: 18px !important; }

/* ── Meta Row ── */
.form-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 0.25rem;
}

.remember-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    font-size: 0.78rem;
    color: rgba(255,255,255,0.45);
    font-weight: 400;
    user-select: none;
}
.remember-label input[type="checkbox"] { display: none; }

.checkbox-custom {
    width: 18px; height: 18px;
    border-radius: 5px;
    border: 1.5px solid rgba(255,255,255,0.15);
    background: rgba(255,255,255,0.04);
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s;
    flex-shrink: 0;
}
.remember-label input:checked ~ .checkbox-custom,
.remember-label:has(input:checked) .checkbox-custom {
    background: #059669;
    border-color: #059669;
}
.checkbox-custom .material-symbols-outlined { font-size: 12px !important; color: #fff; }

.forgot-link {
    font-size: 0.75rem;
    font-weight: 600;
    color: #34d399;
    text-decoration: none;
    transition: color 0.2s;
}
.forgot-link:hover { color: #6ee7b7; }

/* ── Submit Button ── */
.submit-btn {
    width: 100%;
    margin-top: 0.5rem;
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: #fff;
    border: none;
    border-radius: 14px;
    font-family: 'arial', sans-serif;
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    transition: all 0.25s;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(5,150,105,0.35);
}
.submit-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.08), transparent);
    opacity: 0;
    transition: opacity 0.2s;
}
.submit-btn:hover:not(:disabled)::before { opacity: 1; }
.submit-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 14px 32px rgba(5,150,105,0.45);
}
.submit-btn:active:not(:disabled) { transform: translateY(0); }
.submit-btn:disabled {
    opacity: 0.45;
    cursor: not-allowed;
    box-shadow: none;
}
.submit-btn .material-symbols-outlined { font-size: 18px !important; }

.btn-spinner { animation: spin 0.8s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

.btn-arrow {
    margin-left: auto;
    opacity: 0.6;
    transition: transform 0.2s, opacity 0.2s;
}
.submit-btn:hover:not(:disabled) .btn-arrow {
    transform: translateX(4px);
    opacity: 1;
}

/* ── Form Footer ── */
.form-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    font-size: 0.68rem;
    color: rgba(255,255,255,0.2);
    margin-top: 1.5rem;
    font-weight: 300;
}
.form-footer .material-symbols-outlined { font-size: 14px !important; }

/* ── Responsive ── */
@media (max-width: 700px) {
    .brand-panel { display: none; }
    .auth-card { border-radius: 20px; }
    .form-panel { padding: 2rem 1.5rem; }
}
</style>