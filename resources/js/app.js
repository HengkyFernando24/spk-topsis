import './bootstrap.js';
import { createApp } from 'vue';



import Login from './Pages/Login.vue';
import Dashboard from './Pages/Dashboard.vue';
// Perbaikan path: arahkan ke folder Components
import KriteriaManager from './Components/KriteriaManager.vue';
import Perhitungan from './Components/Perhitungan.vue';
import TopsisTable from './Components/TopsisTable.vue';


const app = createApp({});
app.component('login-page', Login);
app.component('dashboard-page', Dashboard);
app.component('kriteria-manager', KriteriaManager);
app.component('perhitungan-page', Perhitungan);
app.component('topsis-table', TopsisTable);

app.mount('#app');
