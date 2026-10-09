import './bootstrap';

import Alpine from 'alpinejs';
import { Chart, registerables } from 'chart.js';

window.Alpine = Alpine;

Alpine.start();

Chart.register(...registerables);
window.Chart = Chart;
