<?php
require "connect.php";
require_once 'income.php';
require_once 'expense.php';

session_start();


$database = new Database();

$db = $database->connect();

$income = new Income($db);
$expense = new Expense($db);

if (isset($_POST['submit_transaction'])) {
    if ($_POST['type'] == 'income') {

        $user_id = $_SESSION['user_id'];
        $amount = $_POST['amount'];
        $description = $_POST['description'];
        $date = $_POST['date'];
        $category = $_POST['category'];

        if ($income->create($user_id, $amount, $description, $date, $category)) {
            echo "
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Toastify({
                            text: 'Transaction Added Successfully! 🎉',
                            duration: 3000,
                            gravity: 'top', // `top` or `bottom`
                            position: 'center', // `left`, `center` or `right`
                            style: {
                                background: 'linear-gradient(to right, #00b09b, #96c93d)',
                                borderRadius: '10px',
                            }
                        }).showToast();
                    });</script>";
        }
    } else if ($_POST['type'] == 'expense') {

        $user_id = $_SESSION['user_id'];
        $amount = $_POST['amount'];
        $description = $_POST['description'];
        $date = $_POST['date'];
        $category = $_POST['category'];

        if ($expense->create($user_id, $amount, $description, $date, $category)) {
            echo "
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Toastify({
                            text: 'Transaction Added Successfully! 🎉',
                            duration: 3000,
                            gravity: 'top', // `top` or `bottom`
                            position: 'center', // `left`, `center` or `right`
                            style: {
                                background: 'linear-gradient(to right, #00b09b, #96c93d)',
                                borderRadius: '10px',
                            }
                        }).showToast();
                    });</script>";
        }
    }
}










// income total amount
$income_sum = "SELECT sum(amount) AS total_income FROM income";

// $result_income = mysqli_query($connect, $income_sum);
// $income_total = 0;
// if ($result_income) {
//     $row = mysqli_fetch_assoc($result_income);
//     $income_total = $row['total_income'];
// }
// expense total amount
$expense_sum = "SELECT sum(amount) AS total_expense FROM expense";

// $result_expense = mysqli_query($connect, $expense_sum);
// $expense_total = 0;
// if ($result_expense) {
//     $row = mysqli_fetch_assoc($result_expense);
//     $expense_total = $row['total_expense'];
// }

// update infos of income
if (!empty($_POST['income-new-submit'])) {
    $amount = $_POST['income-new-amount'];
    $description = $_POST['income-new-description'];
    $date = $_POST['income-new-date'];
    $id = $_POST['id'];
    $sql = "UPDATE income SET amount = $amount WHERE id = $id";

    mysqli_query($connect, $sql);
}
// update infos of expense
if (!empty($_POST['expense-new-submit'])) {
    $amount = $_POST['expense-new-amount'];
    $description = $_POST['expense-new-description'];
    $date = $_POST['expense-new-date'];
    $id = $_POST['id'];
    $sql = "UPDATE expense SET amount = $amount WHERE id = $id";

    mysqli_query($connect, $sql);
}

// delete infos of income
if (!empty($_POST['income-delete'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM income WHERE id = $id";

    mysqli_query($connect, $sql);
}
// delete infos of expense
if (!empty($_POST['expense-delete'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM expense WHERE id = $id";

    mysqli_query($connect, $sql);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Wallet</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://kit.fontawesome.com/559afa4763.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style type="text/tailwindcss">
        body { 
            background: radial-gradient(circle at top left, #f3f4f6, #d1d5db);
            min-height: 100vh;
        }

        /* Glassmorphism Classes */
        .glass-panel {
            @apply bg-white/70 backdrop-blur-md border border-white/50 shadow-sm transition-all duration-300;
        }
        .glass-card {
             @apply glass-panel rounded-2xl p-6 hover:shadow-md hover:bg-white/80;
        }

        /* Input Styling */
        .form-input {
            @apply w-full rounded-xl border border-gray-200 bg-white/60 px-4 py-3 text-sm 
            focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all placeholder-gray-400 font-medium;
        }

        /* Animations */
        @keyframes popIn {
            0% { opacity: 0; transform: scale(0.95); }
            100% { opacity: 1; transform: scale(1); }
        }
        .animate-pop { animation: popIn 0.2s ease-out forwards; }
    </style>
</head>

<body class="font-sans text-gray-800 antialiased">

    <nav class="sticky top-4 z-40 px-4 mb-8">
        <div class="glass-panel max-w-7xl mx-auto px-6 py-4 flex justify-between items-center rounded-2xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                    <i class="fa-solid fa-wallet text-lg"></i>
                </div>
                <span class="text-xl font-bold tracking-tight text-gray-900">Smart Wallet</span>
            </div>

            <button onclick="openModal()"
                class="bg-gray-900 hover:bg-black text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-lg hover:shadow-xl transition-all active:scale-95 flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>New Transaction</span>
            </button>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 pb-12 space-y-8">

        <header class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Welcome back, <span class="text-blue-600"><?php echo $_SESSION['user_name'] ?></span>! 👋
                </h1>
                <p class="text-gray-500 mt-1 font-medium">Overview of your financial status.</p>
            </div>
            <div class="glass-panel px-4 py-2 rounded-xl text-sm font-medium text-gray-600 flex items-center gap-2">
                <i class="fa-regular fa-calendar"></i> Today
            </div>
        </header>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="glass-card flex flex-col justify-between h-40 group cursor-default">
                <div class="flex justify-between items-start">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg"><i class="fa-solid fa-scale-balanced text-xl"></i></div>
                    <span class="text-xs font-semibold bg-blue-50 text-blue-600 px-2 py-1 rounded-md">Net</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Balance</p>
                    <h2 class="text-4xl font-bold text-gray-900 tracking-tight">$ 25,890.00</h2>
                </div>
            </div>

            <div class="glass-card flex flex-col justify-between h-40 group cursor-default">
                <div class="flex justify-between items-start">
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg"><i class="fa-solid fa-arrow-trend-up text-xl"></i></div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Income</p>
                    <h2 class="text-3xl font-bold text-emerald-600 tracking-tight">+$ <?php echo $income->sum($_SESSION['user_id'])?> </h2>
                </div>
            </div>

            <div class="glass-card flex flex-col justify-between h-40 group cursor-default">
                <div class="flex justify-between items-start">
                    <div class="p-2 bg-rose-50 text-rose-600 rounded-lg"><i class="fa-solid fa-arrow-trend-down text-xl"></i></div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Expense</p>
                    <h2 class="text-3xl font-bold text-rose-600 tracking-tight">-$ <?php echo $expense->sum($_SESSION['user_id'])?></h2>
                </div>
            </div>
        </section>

        <section class="glass-card">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg text-gray-800">Recent Transactions</h3>
                <a href="#" class="text-sm text-blue-600 font-semibold hover:text-blue-800 transition-colors">See All</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-xs text-gray-500 border-b border-gray-200">
                            <th class="py-3 font-semibold uppercase pl-2">Description</th>
                            <th class="py-3 font-semibold uppercase">Category</th>
                            <th class="py-3 font-semibold uppercase">Date</th>
                            <th class="py-3 font-semibold uppercase">Type</th>
                            <th class="py-3 font-semibold uppercase text-right pr-2">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">

                        <tr class='group hover:bg-gray-50/50 transition-colors'>
                            <td class='py-4'>
                                <div class='flex items-center gap-3'>
                                    <div class='w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs'>
                                        <i class='fa-solid fa-arrow-down'></i>
                                    </div>
                                    <span class='font-medium text-gray-700'>Freelance Project</span>
                                </div>
                            </td>
                            <td class='py-4'>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                    💻 Freelance
                                </span>
                            </td>
                            <td class='py-4 text-gray-500'>2024-05-15</td>
                            <td class='py-4'>
                                <span class='text-xs font-bold px-2 py-1 rounded-md bg-emerald-50 text-emerald-600 uppercase tracking-wide border border-emerald-100'>
                                    Income
                                </span>
                            </td>
                            <td class='py-4 text-right font-bold text-emerald-600'>
                                + $ 1,200.00
                            </td>
                        </tr>

                        <tr class='group hover:bg-gray-50/50 transition-colors'>
                            <td class='py-4'>
                                <div class='flex items-center gap-3'>
                                    <div class='w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs'>
                                        <i class='fa-solid fa-arrow-up'></i>
                                    </div>
                                    <span class='font-medium text-gray-700'>Grocery Shopping</span>
                                </div>
                            </td>
                            <td class='py-4'>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                    🍔 Food
                                </span>
                            </td>
                            <td class='py-4 text-gray-500'>2024-05-12</td>
                            <td class='py-4'>
                                <span class='text-xs font-bold px-2 py-1 rounded-md bg-rose-50 text-rose-600 uppercase tracking-wide border border-rose-100'>
                                    Expense
                                </span>
                            </td>
                            <td class='py-4 text-right font-bold text-rose-600'>
                                - $ 150.00
                            </td>
                        </tr>

                        <tr class='group hover:bg-gray-50/50 transition-colors'>
                            <td class='py-4'>
                                <div class='flex items-center gap-3'>
                                    <div class='w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs'>
                                        <i class='fa-solid fa-arrow-down'></i>
                                    </div>
                                    <span class='font-medium text-gray-700'>Uber Ride</span>
                                </div>
                            </td>
                            <td class='py-4'>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                    🚗 Transport
                                </span>
                            </td>
                            <td class='py-4 text-gray-500'>2024-05-10</td>
                            <td class='py-4'>
                                <span class='text-xs font-bold px-2 py-1 rounded-md bg-rose-50 text-rose-600 uppercase tracking-wide border border-rose-100'>
                                    Expense
                                </span>
                            </td>
                            <td class='py-4 text-right font-bold text-rose-600'>
                                - $ 25.00
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </section>

        <section class="glass-card">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg text-gray-800">Analytics Overview</h3>
                <span class="text-sm text-gray-400">Monthly Trend</span>
            </div>
            <div class="h-80 w-full relative">
                <canvas id="chart"></canvas>
            </div>
        </section>

    </main>

    <div id="modal" class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300 px-4">

        <form class="glass-panel animate-pop bg-white w-full max-w-md rounded-3xl p-8 relative shadow-2xl" method="post" action="#">

            <button type="button" onclick="closeModal()"
                class="absolute top-6 right-6 p-2 rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors cursor-pointer">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>

            <div class="mb-8">
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-900">Add Income</h2>
                <p id="modalSub" class="text-gray-500 text-sm mt-1">Fill in the details below.</p>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Transaction Type</label>
                <div class="relative">
                    <select id="type" name="type" onchange="switchType()" class="form-input appearance-none cursor-pointer font-bold text-gray-700">
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                    <div class="absolute right-4 top-3.5 pointer-events-none text-gray-500">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Category</label>
                <div class="relative">
                    <select name="category" class="form-input appearance-none cursor-pointer font-bold text-gray-700">
                        <option value="food">🍔 Food & Dining</option>
                        <option value="shopping">🛍️ Shopping</option>
                        <option value="transport">🚗 Transport</option>
                        <option value="entertainment">🎬 Entertainment</option>
                        <option value="bills">💡 Bills & Utilities</option>
                        <option value="salary">💰 Salary</option>
                        <option value="freelance">💻 Freelance</option>
                        <option value="other">📦 Other</option>
                    </select>
                    <div class="absolute right-4 top-3.5 pointer-events-none text-gray-500">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-3 text-gray-400 font-bold">$</span>
                    <input type="number" name="amount" step="0.01" placeholder="0.00" class="form-input pl-8 font-bold text-lg text-gray-800" required>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Description</label>
                <input type="text" name="description" placeholder="e.g. Weekly Groceries" class="form-input" required>
            </div>

            <div class="mb-8">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Date</label>
                <input type="date" name="date" class="form-input text-gray-600">
            </div>

            <button type="submit" name="submit_transaction" id="submitBtn"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl shadow-lg hover:shadow-xl transition-all active:scale-95 flex justify-center items-center gap-2 cursor-pointer">
                <span>Add Income</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </form>
    </div>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        const modal = document.getElementById('modal');
        const typeSelect = document.getElementById('type');
        const title = document.getElementById('modalTitle');
        const sub = document.getElementById('modalSub');
        const btn = document.getElementById('submitBtn');
        const btnText = btn.querySelector('span');

        function openModal() {
            modal.classList.remove('hidden');
            const dateInput = document.querySelector('input[type="date"]');
            if (!dateInput.value) dateInput.valueAsDate = new Date();
        }

        function closeModal() {
            modal.classList.add('hidden');
        }
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeModal();
        });

        function switchType() {
            const isIncome = typeSelect.value === 'income';
            title.innerText = isIncome ? 'Add Income' : 'Add Expense';
            sub.innerText = isIncome ? 'Track your earnings.' : 'Track your spending.';
            btnText.innerText = isIncome ? 'Add Income' : 'Add Expense';
            title.className = `text-2xl font-bold ${isIncome ? 'text-blue-600' : 'text-rose-600'}`;
            btn.className = `w-full font-bold py-3.5 rounded-xl shadow-lg hover:shadow-xl transition-all active:scale-95 flex justify-center items-center gap-2 cursor-pointer text-white ${isIncome ? 'bg-blue-600 hover:bg-blue-700' : 'bg-rose-600 hover:bg-rose-700'}`;
        }

        // Chart Config
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
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Inter'
                            },
                            color: '#9ca3af'
                        }
                    },
                    y: {
                        grid: {
                            borderDash: [4, 4],
                            color: '#e5e7eb'
                        },
                        ticks: {
                            font: {
                                family: 'Inter'
                            },
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
    </script>
</body>

</html>