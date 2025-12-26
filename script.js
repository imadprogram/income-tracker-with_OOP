// --- DOM ELEMENTS ---
const modal = document.getElementById('modal');
const editModal = document.getElementById('edit');
const catModal = document.querySelector('.catModal');

const typeSelect = document.getElementById('type');
const title = document.getElementById('modalTitle');
const sub = document.getElementById('modalSub');
const btn = document.getElementById('submitBtn');
const btnText = btn.querySelector('span');

// Category Buttons
const category_btn = document.querySelector('.add-category');
const close_category = document.querySelector('#close_it');

// Edit Buttons (Selects all buttons with class .edit_btn from both tables)
const editBtns = document.querySelectorAll('.edit_btn');

// --- EVENT LISTENERS ---

// 1. Add Category Modal
if (category_btn) {
    category_btn.addEventListener('click', () => {
        catModal.classList.remove('hidden');
    });
}
if (close_category) {
    close_category.addEventListener('click', () => {
        catModal.classList.add('hidden');
    });
}

// 2. Edit Transaction Modal Logic
// Inside script.js

editBtns.forEach(button => {
    button.addEventListener('click', function() {
        editModal.classList.remove('hidden');

        // Get data
        const id = this.getAttribute('data-id');
        const type = this.getAttribute('data-type'); // Get 'income' or 'expense'

        // Fill Hidden Inputs
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_type').value = type; 
    });
});

// 3. Main Modal Logic (Add Transaction)
function openModal() {
    modal.classList.remove('hidden');
    const dateInput = document.querySelector('input[type="date"]');
    if (dateInput && !dateInput.value) dateInput.valueAsDate = new Date();
}

function closeModal() {
    modal.classList.add('hidden');
}

// 4. Edit Modal Close Logic
function closeEditModal() {
    editModal.classList.add('hidden');
}

// Close on background click
window.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
    if (e.target === editModal) closeEditModal();
    if (e.target === catModal) catModal.classList.add('hidden');
});

// Close on Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeModal();
        closeEditModal();
        if(catModal) catModal.classList.add('hidden');
    }
});

// 5. Toggle Income/Expense UI
function switchType() {
    const isIncome = typeSelect.value === 'income';
    title.innerText = isIncome ? 'Add Income' : 'Add Expense';
    sub.innerText = isIncome ? 'Track your earnings.' : 'Track your spending.';
    btnText.innerText = isIncome ? 'Add Income' : 'Add Expense';
    title.className = `text-2xl font-bold ${isIncome ? 'text-blue-600' : 'text-rose-600'}`;
    btn.className = `w-full font-bold py-3.5 rounded-xl shadow-lg transition-all active:scale-95 flex justify-center items-center gap-2 cursor-pointer text-white ${isIncome ? 'bg-blue-600 hover:bg-blue-700' : 'bg-rose-600 hover:bg-rose-700'}`;
}

// --- CHART CONFIG ---
const ctx = document.getElementById('chart').getContext('2d');
let gradient = ctx.createLinearGradient(0, 0, 0, 400);
gradient.addColorStop(0, 'rgba(37, 99, 235, 0.2)');
gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Balance',
            data: [12000, 19000, 15000, 22000, 18000, 24847],
            borderColor: '#2563eb',
            backgroundColor: gradient,
            borderWidth: 3,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: '#2563eb',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: {
                    font: { family: 'Inter' },
                    color: '#9ca3af'
                }
            },
            y: {
                grid: {
                    borderDash: [4, 4],
                    color: '#e5e7eb'
                },
                ticks: {
                    font: { family: 'Inter' },
                    color: '#9ca3af',
                    callback: (value) => '$' + value / 1000 + 'k'
                },
                beginAtZero: true
            }
        },
        interaction: {
            intersect: false,
            mode: 'index'
        }
    }
});