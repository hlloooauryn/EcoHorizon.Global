import './bootstrap';
import L from 'leaflet';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

window.L = L;
window.Chart = Chart;

// Fix standard Leaflet default marker icon paths when bundled with Vite
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
});
