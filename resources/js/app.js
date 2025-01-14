import './bootstrap';
import './attendance';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Function to trigger profile picture animation
function triggerProfilePictureAnimation() {
    const profilePicture = document.getElementById('profile-picture');
    
    // Add animation classes
    profilePicture.classList.add('scale-150', 'rotate-180');
    
    // Remove animation classes after 0.5 seconds
    setTimeout(() => {
        profilePicture.classList.remove('scale-150', 'rotate-180');
    }, 500);
}

// Example: Attach animation to file input change
document.getElementById('profile_picture').addEventListener('change', (event) => {
    if (event.target.files.length > 0) {
        // Trigger the animation
        triggerProfilePictureAnimation();
    }
});

async function fetchReports() {
    try {
        const response = await fetch('/api/reports');
        const reports = await response.json();

        displayReports(reports);
        updateChart(reports);
    } catch (error) {
        console.error('Error fetching reports:', error);
    }
}

function displayReports(reports) {
    const container = document.getElementById('reports');
    container.innerHTML = ''; // Clear previous reports

    reports.forEach(report => {
        const reportEl = document.createElement('div');
        reportEl.classList.add('flex', 'justify-between', 'text-sm');
        reportEl.innerHTML = `
            <span>${report.name}</span>
            <span class="${report.type === 'income' ? 'text-green-400' : 'text-red-400'}">
                RM${report.amount.toFixed(2)}
            </span>
        `;
        container.appendChild(reportEl);
    });
}

function updateChart(reports) {
    const incomes = reports.filter(t => t.type === 'income').reduce((sum, t) => sum + t.amount, 0);
    const expenses = reports.filter(t => t.type === 'expense').reduce((sum, t) => sum + t.amount, 0);

    const ctx = document.getElementById('pieChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Incomes', 'Expenses'],
            datasets: [{
                data: [incomes, expenses],
                backgroundColor: ['#00ffab', '#ff6b6b']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: '#fff'
                    }
                }
            }
        }
    });

    // Update balance
    const totalBalance = incomes - expenses;
    document.getElementById('balanceAmount').textContent = `RM${totalBalance.toFixed(2)}`;
    document.getElementById('balancePercentage').textContent = `${((incomes / (incomes + expenses)) * 100).toFixed(2)}%`;
}

// Load data when the page is ready
document.addEventListener('DOMContentLoaded', fetchReports);
