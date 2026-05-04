import './bootstrap';
import './echo';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    if (!window.Echo) {
        return;
    }

    const updateDashboardStats = () => {
        fetch('/dashboard/stats')
            .then(response => response.json())
            .then(data => {
                const totalEquipment = document.getElementById('total-equipment');
                const borrowedItems = document.getElementById('borrowed-items');
                const damagedItems = document.getElementById('damaged-items');
                const maintenanceItems = document.getElementById('maintenance-items');

                if (totalEquipment) {
                    totalEquipment.textContent = data.totalEquipment;
                }

                if (borrowedItems) {
                    borrowedItems.textContent = data.borrowedItems;
                }

                if (damagedItems) {
                    damagedItems.textContent = data.damagedItems;
                }

                if (maintenanceItems) {
                    maintenanceItems.textContent = data.maintenanceItems;
                }
            })
            .catch(error => {
                console.error('Dashboard stats update failed:', error);
            });
    };

    window.Echo.channel('inventory')
        .listen('.updated', () => {
            updateDashboardStats();
        });
});