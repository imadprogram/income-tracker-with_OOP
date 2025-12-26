<?php
require "classes/connect.php";
require_once 'classes/income.php';
require_once 'classes/expense.php';
require_once 'classes/category.php';

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

$all_categories = new Category($db);


$income_categories = $all_categories->getAllCategories('income');
$expense_categories = $all_categories->getAllCategories('expense');
$categories = $all_categories->getAllCategories('all');

// ADD NEW CATEGORY

if(isset($_POST['submit_category'])){
    $type = $_POST['type'];
    $new_cat = $_POST['new_category'];

    if($all_categories->create($new_cat , $type)){
        echo "the ". $type . "category has added !";
    }
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

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
            background: radial-gradient(circle at top left, #f3f4f6, #e5e7eb);
            min-height: 100vh;
        }

        /* Glassmorphism */
        .glass-panel {
            @apply bg-white/80 backdrop-blur-xl border border-white/60 shadow-sm transition-all duration-300;
        }
        .glass-card {
             @apply glass-panel rounded-2xl p-6 hover:shadow-md;
        }

        /* Scrollbar */
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { @apply bg-gray-200 rounded-full; }
        .custom-scroll::-webkit-scrollbar-track { @apply bg-transparent; }

        /* Inputs */
        .form-input {
            @apply w-full rounded-xl border border-gray-200 bg-white/50 px-4 py-3 text-sm 
            focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all font-medium;
        }

        /* Modal Animation */
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

            <button class='add-category rounded-lg shadow-xl px-4 py-2'>
                add category
            </button>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 pb-12 space-y-8">

        <header class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Welcome back, <span class="text-blue-600"><?php echo $_SESSION['user_name'] ?></span>! 👋
                </h1>
                <p class="text-gray-500 mt-1 font-medium">Here is your financial overview.</p>
            </div>
            <div class="glass-panel px-4 py-2 rounded-xl text-sm font-medium text-gray-600 flex items-center gap-2">
                <i class="fa-regular fa-calendar"></i> Today
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="flex flex-col gap-6">
                <div class="glass-card flex flex-col justify-between h-48">
                    <div class="flex justify-between items-start">
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl text-xl"><i class="fa-solid fa-scale-balanced"></i></div>
                        <span class="text-xs font-bold bg-blue-100 text-blue-600 px-3 py-1 rounded-lg">Net Worth</span>
                    </div>
                    <div>
                        <p class="text-gray-500 font-medium mb-1">Total Balance</p>
                        <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight">$ <?php echo $income->sum($_SESSION['user_id']) - $expense->sum($_SESSION['user_id']) ?></h2>
                    </div>
                </div>

                <div class="glass-card h-full min-h-[300px] flex flex-col items-center justify-center text-center relative overflow-hidden bg-gradient-to-b from-white to-orange-50/50">
                    <div class="absolute top-1/4 left-1/4 w-32 h-32 bg-orange-200 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-pulse"></div>
                    <div class="absolute bottom-1/4 right-1/4 w-32 h-32 bg-yellow-200 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-pulse" style="animation-delay: 1s"></div>

                    <div class="relative z-10 mb-4 transform hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-cat text-7xl text-orange-500 drop-shadow-lg"></i>
                        <div class="absolute -top-6 -right-12 bg-white px-3 py-1.5 rounded-xl rounded-bl-none shadow-sm text-xs font-bold text-gray-600 border border-gray-100 animate-bounce">
                            Save more! 😺
                        </div>
                    </div>

                    <div class="relative z-10">
                        <h3 class="text-xl font-bold text-gray-800 mb-1">Wise Saver</h3>
                        <p class="text-sm text-gray-500 px-4">"A penny saved is a penny earned." Keep tracking to stay ahead!</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-6">
                <div class="glass-card h-48 flex flex-col justify-between border-l-4 border-l-emerald-400">
                    <div class="flex justify-between items-start">
                        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl text-xl"><i class="fa-solid fa-arrow-trend-up"></i></div>
                        <span class="text-xs font-bold bg-emerald-100 text-emerald-600 px-3 py-1 rounded-lg">In</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Total Income</p>
                        <h2 class="text-3xl font-bold text-emerald-600 tracking-tight">+$ <?php echo $income->sum($_SESSION['user_id']) ?></h2>
                    </div>
                </div>

                <div class="glass-card flex-grow flex flex-col h-[340px] p-0 overflow-hidden">

                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-white/50">
                        <h3 class="font-bold text-emerald-700 text-sm uppercase tracking-wide">Income List</h3>

                        <form action="dashboard.php" method="GET" class="flex items-center gap-2">
                            <div class="relative">
                                <select name="filter_income" class="bg-white border border-gray-200 text-gray-600 text-xs rounded-lg pl-2 pr-6 py-1.5 focus:ring-1 focus:ring-emerald-500 outline-none appearance-none cursor-pointer">
                                    <option value="all">All Categories</option>
                                    <?php
                                    foreach ($income_categories as $cat) {
                                        echo "<option value=" . $cat['id'] . ">" . $cat['name'] . "</option>";
                                    }
                                    ?>
                                    <!-- <option value="all">All Categories</option>
                                    <option value="food">🍔 Food & Dining</option>
                                    <option value="shopping">🛍️ Shopping</option>
                                    <option value="transport">🚗 Transport</option>
                                    <option value="entertainment">🎬 Entertainment</option>
                                    <option value="bills">💡 Bills & Utilities</option>
                                    <option value="salary">💰 Salary</option>
                                    <option value="freelance">💻 Freelance</option>
                                    <option value="other">📦 Other</option> -->

                                </select>
                                <i class="fa-solid fa-filter absolute right-2 top-2 text-[10px] text-gray-400 pointer-events-none"></i>
                            </div>
                            <button type="submit" class="bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-200 text-xs font-bold px-3 py-1.5 rounded-lg transition-colors cursor-pointer">
                                Apply
                            </button>
                        </form>
                    </div>

                    <div class="overflow-y-auto custom-scroll flex-grow p-2">
                        <table class="w-full text-left">
                            <tbody class="text-sm divide-y divide-gray-50">
                                <?php

                                if (isset($_GET['filter_income'])) {
                                    if ($_GET['filter_income'] == 'all') {
                                        goto here;
                                    }
                                    $selected = $_GET['filter_income'];
                                    $result = $income->getByCategory($_SESSION['user_id'], $selected);
                                    if (count($result) > 0) {
                                        foreach ($result as $row) {
                                            echo "
                                        <tr class='group hover:bg-rose-50/30 transition-colors relative rounded-lg'>
                                            <td class='py-3 px-3'>
                                                <div class='font-semibold text-gray-700'>" . $row['category_name'] . "</div>
                                                <div class='text-[10px] text-gray-400'>" . $row['date'] . "</div>
                                            </td>
                                            <td class='py-3 px-3 text-right'>
                                                <span class='font-bold text-rose-600 block'>- $" . $row['amount'] . "</span>

                                                <div class='absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity bg-white/90 shadow-sm p-1 rounded-lg backdrop-blur-sm'>
                                                    <form action='delete_expense.php' method='POST' onsubmit='return confirm('Delete this expense?');'>
                                                        <input type='hidden' name='id' value='1'>
                                                        <button type='submit' class='w-7 h-7 rounded-md bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all'>
                                                            <i class='fa-solid fa-trash text-xs'></i>
                                                        </button>
                                                    </form>
                                                    <button class='w-7 h-7 rounded-md bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-all'>
                                                        <i class='fa-solid fa-pen text-xs'></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                                    ";
                                        }
                                    } else {
                                        echo "<p>there is nothing to show !</p>";
                                    }
                                } else {
                                    here:

                                    $result = $income->getAll($_SESSION['user_id']);
                                    if (count($result) > 0) {
                                        foreach ($result as $row) {
                                            echo "
                                                    <tr class='group hover:bg-rose-50/30 transition-colors relative rounded-lg'>
                                                        <td class='py-3 px-3'>
                                                            <div class='font-semibold text-gray-700'>" . $row['category_name'] . "</div>
                                                            <div class='text-[10px] text-gray-400'>" . $row['date'] . "</div>
                                                        </td>
                                                        <td class='py-3 px-3 text-right'>
                                                            <span class='font-bold text-rose-600 block'>- $" . $row['amount'] . "</span>

                                                            <div class='absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity bg-white/90 shadow-sm p-1 rounded-lg backdrop-blur-sm'>
                                                                <form action='delete_expense.php' method='POST' onsubmit='return confirm('Delete this expense?');'>
                                                                    <input type='hidden' name='id' value='1'>
                                                                    <button type='submit' class='w-7 h-7 rounded-md bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all'>
                                                                        <i class='fa-solid fa-trash text-xs'></i>
                                                                    </button>
                                                                </form>
                                                                <button class='w-7 h-7 rounded-md bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-all'>
                                                                    <i class='fa-solid fa-pen text-xs'></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                        ";
                                        }
                                    } else {
                                        echo "<p class='text-gray-500'>there is nothing to show !</p>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-6">
                <div class="glass-card h-48 flex flex-col justify-between border-l-4 border-l-rose-400">
                    <div class="flex justify-between items-start">
                        <div class="p-3 bg-rose-50 text-rose-600 rounded-xl text-xl"><i class="fa-solid fa-arrow-trend-down"></i></div>
                        <span class="text-xs font-bold bg-rose-100 text-rose-600 px-3 py-1 rounded-lg">Out</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Total Expense</p>
                        <h2 class="text-3xl font-bold text-rose-600 tracking-tight">-$ <?php echo $expense->sum($_SESSION['user_id']) ?></h2>
                    </div>
                </div>

                <div class="glass-card flex-grow flex flex-col h-[340px] p-0 overflow-hidden">

                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-white/50">
                        <h3 class="font-bold text-rose-700 text-sm uppercase tracking-wide">Expense List</h3>

                        <form action="dashboard.php" method="GET" class="flex items-center gap-2">
                            <div class="relative">
                                <select name="filter_expense" class="bg-white border border-gray-200 text-gray-600 text-xs rounded-lg pl-2 pr-6 py-1.5 focus:ring-1 focus:ring-rose-500 outline-none appearance-none cursor-pointer">
                                    <option value="all">All Categories</option>
                                    <?php
                                    foreach ($expense_categories as $cat) {
                                        echo "<option value=" . $cat['id'] . ">" . $cat['name'] . "</option>";
                                    }
                                    ?>
                                    <!-- <option value="food">🍔 Food & Dining</option>
                                    <option value="shopping">🛍️ Shopping</option>
                                    <option value="transport">🚗 Transport</option>
                                    <option value="entertainment">🎬 Entertainment</option>
                                    <option value="bills">💡 Bills & Utilities</option>
                                    <option value="salary">💰 Salary</option>
                                    <option value="freelance">💻 Freelance</option>
                                    <option value="other">📦 Other</option> -->

                                </select>
                                <i class="fa-solid fa-filter absolute right-2 top-2 text-[10px] text-gray-400 pointer-events-none"></i>
                            </div>
                            <button type="submit" class="bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200 text-xs font-bold px-3 py-1.5 rounded-lg transition-colors cursor-pointer">
                                Apply
                            </button>
                        </form>
                    </div>

                    <div class="overflow-y-auto custom-scroll flex-grow p-2">
                        <table class="w-full text-left">
                            <tbody class="text-sm divide-y divide-gray-50">
                                <?php
                                if (isset($_GET['filter_expense'])) {
                                    if ($_GET['filter_expense'] == 'all') {
                                        goto there;
                                    }
                                    $selected = $_GET['filter_expense'];
                                    $result = $expense->getByCategory($_SESSION['user_id'], $selected);
                                    if (count($result) > 0) {
                                        foreach ($result as $row) {
                                            echo "
                                        <tr class='group hover:bg-rose-50/30 transition-colors relative rounded-lg'>
                                            <td class='py-3 px-3'>
                                                <div class='font-semibold text-gray-700'>" . $row['category_name'] . "</div>
                                                <div class='text-[10px] text-gray-400'>" . $row['date'] . "</div>
                                            </td>
                                            <td class='py-3 px-3 text-right'>
                                                <span class='font-bold text-rose-600 block'>- $" . $row['amount'] . "</span>

                                                <div class='absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity bg-white/90 shadow-sm p-1 rounded-lg backdrop-blur-sm'>
                                                    <form action='delete_expense.php' method='POST' onsubmit='return confirm('Delete this expense?');'>
                                                        <input type='hidden' name='id' value='1'>
                                                        <button type='submit' class='w-7 h-7 rounded-md bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all'>
                                                            <i class='fa-solid fa-trash text-xs'></i>
                                                        </button>
                                                    </form>
                                                    <button class='w-7 h-7 rounded-md bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-all'>
                                                        <i class='fa-solid fa-pen text-xs'></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                                    ";
                                        }
                                    } else {
                                        echo "<p>there is nothing to show !</p>";
                                    }
                                } else {
                                    there:

                                    $result = $expense->getAll($_SESSION['user_id']);

                                    if (count($result) > 0) {
                                        foreach ($result as $row) {
                                            echo "
                                                <tr class='group hover:bg-rose-50/30 transition-colors relative rounded-lg'>
                                                    <td class='py-3 px-3'>
                                                        <div class='font-semibold text-gray-700'>" . $row['category_name'] . "</div>
                                                        <div class='text-[10px] text-gray-400'>" . $row['date'] . "</div>
                                                    </td>
                                                    <td class='py-3 px-3 text-right'>
                                                        <span class='font-bold text-rose-600 block'>- $" . $row['amount'] . "</span>

                                                        <div class='absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity bg-white/90 shadow-sm p-1 rounded-lg backdrop-blur-sm'>
                                                            <form action='delete_expense.php' method='POST' onsubmit='return confirm('Delete this expense?');'>
                                                                <input type='hidden' name='id' value='1'>
                                                                <button type='submit' class='w-7 h-7 rounded-md bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all'>
                                                                    <i class='fa-solid fa-trash text-xs'></i>
                                                                </button>
                                                            </form>
                                                            <button class='w-7 h-7 rounded-md bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-all'>
                                                                <i class='fa-solid fa-pen text-xs'></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                    ";
                                        }
                                    } else {
                                        echo "<p class='text-gray-500'>there is nothing to show !</p>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <section class="glass-card mt-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg text-gray-800">Analytics Overview</h3>
                <span class="text-sm text-gray-400">Monthly Trend</span>
            </div>
            <div class="h-72 w-full relative">
                <canvas id="chart"></canvas>
            </div>
        </section>

    </main>

    <div id="modal" class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300 px-4">
        <form class="glass-panel animate-pop bg-white w-full max-w-md rounded-3xl p-8 relative shadow-2xl" method="post" action="dashboard.php">
            <button type="button" onclick="closeModal()" class="absolute top-6 right-6 p-2 rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors cursor-pointer">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
            <div class="mb-8">
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-900">Add Income</h2>
                <p id="modalSub" class="text-gray-500 text-sm mt-1">Fill in the details below.</p>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Type</label>
                <div class="relative">
                    <select id="type" name="type" onchange="switchType()" class="form-input appearance-none cursor-pointer font-bold text-gray-700">
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                    <div class="absolute right-4 top-3.5 pointer-events-none text-gray-500"><i class="fa-solid fa-chevron-down text-xs"></i></div>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Category</label>
                <div class="relative">
                    <select name="category" class="form-input appearance-none cursor-pointer font-bold text-gray-700">
                        <?php
                        foreach ($categories as $cat) {
                            echo "<option value=" . $cat['id'] . ">" . $cat['name'] . "</option>";
                        }
                        ?>
                        <!-- <option value="food">🍔 Food & Dining</option>
                        <option value="shopping">🛍️ Shopping</option>
                        <option value="transport">🚗 Transport</option>
                        <option value="entertainment">🎬 Entertainment</option>
                        <option value="bills">💡 Bills & Utilities</option>
                        <option value="salary">💰 Salary</option>
                        <option value="freelance">💻 Freelance</option>
                        <option value="other">📦 Other</option> -->
                    </select>
                    <div class="absolute right-4 top-3.5 pointer-events-none text-gray-500"><i class="fa-solid fa-chevron-down text-xs"></i></div>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-3 text-gray-400 font-bold">$</span>
                    <input type="number" name="amount" step="0.01" placeholder="0.00" class="form-input pl-8 font-bold text-lg text-gray-800" required>
                </div>
            </div>

            <div class="mb-5"><label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Description</label><input type="text" name="description" placeholder="Details" class="form-input" required></div>
            <div class="mb-8"><label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Date</label><input type="date" name="date" class="form-input text-gray-600"></div>

            <button type="submit" name="submit_transaction" id="submitBtn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl shadow-lg transition-all active:scale-95 flex justify-center items-center gap-2 cursor-pointer">
                <span>Add Income</span><i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </form>
    </div>

    <div class="catModal   fixed inset-0 hidden z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300 px-4">
        <form class="glass-panel animate-pop bg-white w-full max-w-md rounded-3xl p-8 relative shadow-2xl" method="post" action="dashboard.php">
            <button type="button" id="close_it" class="absolute top-6 right-6 p-2 rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors cursor-pointer">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
            <div class="mb-8">
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-900">Add Category</h2>
                <p id="modalSub" class="text-gray-500 text-sm mt-1">Fill in the details below.</p>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Type</label>
                <div class="relative">
                    <select id="type" name="type" class="form-input appearance-none cursor-pointer font-bold text-gray-700">
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                    <div class="absolute right-4 top-3.5 pointer-events-none text-gray-500"><i class="fa-solid fa-chevron-down text-xs"></i></div>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Category Name</label>
                <div class="relative">
                    <input required type="text" name="new_category" class="form-input appearance-none cursor-pointer font-bold text-gray-700">
                </div>
            </div>


            <button type="submit" name="submit_category" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl shadow-lg transition-all active:scale-95 flex justify-center items-center gap-2 cursor-pointer">
                <span>Add Category</span><i class="fa-solid fa-arrow-right text-xs"></i>
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

        //////
        const category_btn = document.querySelector('.add-category');
        const close_category = document.querySelector('#close_it');

        category_btn.addEventListener('click', () => {
            document.querySelector('.catModal').classList.remove('hidden')
        })
        close_category.addEventListener('click',()=>{
            document.querySelector('.catModal').classList.add('hidden')
        })
        /////

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
            btn.className = `w-full font-bold py-3.5 rounded-xl shadow-lg transition-all active:scale-95 flex justify-center items-center gap-2 cursor-pointer text-white ${isIncome ? 'bg-blue-600 hover:bg-blue-700' : 'bg-rose-600 hover:bg-rose-700'}`;
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