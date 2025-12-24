<?php
include("connect.php");
//////

/////
?>
<?php

if (isset($_POST["income-submit"])) {
    try {
        $amount = mysqli_real_escape_string($connect, $_POST["income-amount"]);
        $description = mysqli_real_escape_string($connect, $_POST["income-description"]);
        $date = empty($_POST["income-date"]) ? date('y-m-d') : mysqli_real_escape_string($connect, $_POST["income-date"]);
        $sql_insert = "INSERT INTO income (amount , description,  date) VALUES({$amount}, '{$description}', '{$date}')";

        mysqli_query($connect, $sql_insert);
    } catch (mysqli_sql_exception $e) {
        echo $e->getMessage();
    }
}
if (isset($_POST["expense-submit"])) {
    try {
        $amount = mysqli_real_escape_string($connect, $_POST["expense-amount"]);
        $description = mysqli_real_escape_string($connect, $_POST["expense-description"]);
        $date = empty($_POST["expense-date"]) ? date('y-m-d') : mysqli_real_escape_string($connect, $_POST["expense-date"]);
        $sql_insert = "INSERT INTO expense (amount , description,  date) VALUES({$amount}, '{$description}', '{$date}')";

        mysqli_query($connect, $sql_insert);
    } catch (mysqli_sql_exception $e) {
        echo $e->getMessage();
    }
}
// income total amount
$income_sum = "SELECT sum(amount) AS total_income FROM income";

// $result_income = mysqli_query($connect, $income_sum);
$income_total = 0;
if ($result_income) {
    $row = mysqli_fetch_assoc($result_income);
    $income_total = $row['total_income'];
}
// expense total amount
$expense_sum = "SELECT sum(amount) AS total_expense FROM expense";

// $result_expense = mysqli_query($connect, $expense_sum);
$expense_total = 0;
if ($result_expense) {
    $row = mysqli_fetch_assoc($result_expense);
    $expense_total = $row['total_expense'];
}

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
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/559afa4763.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <title>Smart Wallet - Dashboard</title>
</head>
<style type="text/tailwindcss">
    @theme {
        --color-glass-border: rgba(255, 255, 255, 0.1);
    }
    
    /* Background Blobs Animation */
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }

    /* Glows */
    .input-glow:focus {
        box-shadow: 0 0 15px rgba(34, 211, 238, 0.3);
    }
    
    /* Scrollbar styling */
    .custom-scroll::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .custom-scroll::-webkit-scrollbar-track {
        background: rgba(0,0,0,0.1);
    }
    .custom-scroll::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
    }
    .custom-scroll::-webkit-scrollbar-thumb:hover {
        background: rgba(255,255,255,0.4);
    }
</style>

<body class="bg-[#0f172a] text-white font-sans relative overflow-x-hidden min-h-screen">

    <div class="fixed top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <div class="absolute top-0 left-10 w-96 h-96 bg-cyan-500 rounded-full mix-blend-screen filter blur-[100px] opacity-20 animate-blob"></div>
        <div class="absolute top-10 right-10 w-96 h-96 bg-violet-600 rounded-full mix-blend-screen filter blur-[100px] opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-fuchsia-600 rounded-full mix-blend-screen filter blur-[100px] opacity-20 animate-blob animation-delay-4000"></div>
    </div>

    <div class="relative z-10 flex flex-col min-h-screen backdrop-blur-[2px]">
        
        <header class="bg-white/5 border-b border-white/10 backdrop-blur-md sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
                <div class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-400">
                    <i class="fa-solid fa-wallet mr-2"></i>Smart Wallet
                </div>
            </div>
        </header>

        <section class="max-w-7xl mx-auto px-6 py-8 w-full flex-grow flex flex-col gap-8">
            
            <div class="flex flex-wrap gap-5 justify-between items-end">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-1">Dashboard</h1>
                    <p class="text-sm font-sans text-gray-400">Overview of your financial status.</p>
                </div>
                <div class="flex gap-3">
                    <button id="income-btn" class="cursor-pointer px-6 py-2.5 rounded-xl font-semibold text-white bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 shadow-[0_0_15px_rgba(6,182,212,0.3)] hover:shadow-[0_0_20px_rgba(6,182,212,0.5)] transition-all active:scale-95">
                        <i class="fa-solid fa-plus mr-2"></i> Add Income
                    </button>
                    <button id="expense-btn" class="cursor-pointer px-6 py-2.5 rounded-xl font-semibold text-white bg-gradient-to-r from-fuchsia-500 to-purple-600 hover:from-fuchsia-400 hover:to-purple-500 shadow-[0_0_15px_rgba(192,38,211,0.3)] hover:shadow-[0_0_20px_rgba(192,38,211,0.5)] transition-all active:scale-95">
                        <i class="fa-solid fa-minus mr-2"></i> Add Expense
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="relative p-6 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-xl shadow-lg hover:bg-white/10 transition-all duration-300 group overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-cyan-500/20 rounded-full blur-xl group-hover:bg-cyan-500/30 transition-all"></div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-cyan-500/10 rounded-xl text-cyan-400">
                            <i class="fa-solid fa-arrow-trend-up text-xl"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Income Balance</span>
                    </div>
                    <p class="text-3xl font-bold text-white">$ <?php echo $income_total + 0 ?></p>
                </div>

                <div class="relative p-6 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-xl shadow-lg hover:bg-white/10 transition-all duration-300 group overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-fuchsia-500/20 rounded-full blur-xl group-hover:bg-fuchsia-500/30 transition-all"></div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-fuchsia-500/10 rounded-xl text-fuchsia-400">
                            <i class="fa-solid fa-arrow-trend-down text-xl"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Expense Balance</span>
                    </div>
                    <p class="text-3xl font-bold text-white">$ <?php echo $expense_total + 0 ?></p>
                </div>

                <div class="relative p-6 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-xl shadow-lg hover:bg-white/10 transition-all duration-300 group overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/20 rounded-full blur-xl group-hover:bg-emerald-500/30 transition-all"></div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-emerald-500/10 rounded-xl text-emerald-400">
                            <i class="fa-solid fa-wallet text-xl"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Balance</span>
                    </div>
                    <p class="text-3xl font-bold text-white">$ <?php echo $income_total - $expense_total + 0 ?></p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <div class="flex flex-col gap-4">
                    <h3 class="text-xl font-semibold text-cyan-400 pl-2">Recent Incomes</h3>
                    <div id="incomes" class="w-full custom-scroll overflow-y-auto rounded-2xl border border-white/10 bg-slate-900/50 backdrop-blur-md h-96 relative">
                        <table class="w-full text-left">
                            <thead class="sticky top-0 bg-slate-900/90 backdrop-blur-sm z-10 border-b border-white/10">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <?php
                                $incomes = "SELECT * FROM income WHERE MONTH(date) = MONTH(CURRENT_DATE) AND YEAR(date) = YEAR(CURRENT_DATE)";
                                $the_result = mysqli_query($connect, $incomes);
                                if (mysqli_num_rows($the_result) > 0) {
                                    while ($row = mysqli_fetch_assoc($the_result)) {
                                        echo "
                                            <tr data-id=" . $row['id'] . " class='group hover:bg-white/5 transition-colors duration-200 element'>
                                                <td class='px-6 py-4 whitespace-nowrap'>
                                                    <span class='text-cyan-400 font-bold text-lg'>$ " . $row['amount'] . "</span>
                                                    <div class='text-xs text-gray-500 mt-0.5'>" . (isset($row['description']) ? $row['description'] : '') . "</div>
                                                </td>
                                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-300'>
                                                    <div class='flex justify-between items-center w-full'>
                                                        <span>" . $row['date'] . "</span>
                                                        <div class='flex gap-3 opacity-0 group-hover:opacity-100 transition-opacity'>
                                                            <i class='fa-solid fa-pen edit cursor-pointer text-blue-400 hover:text-blue-300'></i>
                                                            <i class='fa-solid fa-trash bin cursor-pointer text-red-400 hover:text-red-300'></i>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        ";
                                    }
                                } else {
                                    echo "<div class='absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-gray-500 text-sm flex flex-col items-center gap-2'>
                                            <i class='fa-regular fa-folder-open text-2xl'></i>
                                            <span>No incomes found</span>
                                          </div>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <h3 class="text-xl font-semibold text-fuchsia-400 pl-2">Recent Expenses</h3>
                    <div id="expenses" class="w-full custom-scroll overflow-y-auto rounded-2xl border border-white/10 bg-slate-900/50 backdrop-blur-md h-96 relative">
                        <table class="w-full text-left">
                            <thead class="sticky top-0 bg-slate-900/90 backdrop-blur-sm z-10 border-b border-white/10">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <?php
                                $expenses = "SELECT * FROM expense WHERE MONTH(date) = MONTH(CURRENT_DATE) AND YEAR(date) = YEAR(CURRENT_DATE)";
                                $the_result = mysqli_query($connect, $expenses);
                                if (mysqli_num_rows($the_result) > 0) {
                                    while ($row = mysqli_fetch_assoc($the_result)) {
                                        echo "
                                            <tr data-id=" . $row['id'] . " class='group hover:bg-white/5 transition-colors duration-200 element'>
                                                <td class='px-6 py-4 whitespace-nowrap'>
                                                    <span class='text-fuchsia-400 font-bold text-lg'>$ " . $row['amount'] . "</span>
                                                    <div class='text-xs text-gray-500 mt-0.5'>" . (isset($row['description']) ? $row['description'] : '') . "</div>
                                                </td>
                                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-300'>
                                                    <div class='flex justify-between items-center w-full'>
                                                        <span class='dates'>" . $row['date'] . "</span>
                                                        <div class='flex gap-3 opacity-0 group-hover:opacity-100 transition-opacity'>
                                                            <i class='fa-solid fa-pen edit cursor-pointer text-blue-400 hover:text-blue-300'></i>
                                                            <i class='fa-solid fa-trash bin cursor-pointer text-red-400 hover:text-red-300'></i>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        ";
                                    }
                                } else {
                                    echo "<div class='absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-gray-500 text-sm flex flex-col items-center gap-2'>
                                            <i class='fa-regular fa-folder-open text-2xl'></i>
                                            <span>No expenses found</span>
                                          </div>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </section>

        <section class="max-w-7xl mx-auto px-6 pb-12 w-full">
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-xl">
                <h3 class="text-xl font-semibold text-white mb-6 pl-2 border-l-4 border-cyan-500">Statistics Overview</h3>
                <div class="h-80 w-full">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </section>

    </div>

    <div class="income-form-bg hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex justify-center items-center">
        <form action="index.php" method="post" class="relative bg-[#0f172a] border border-white/10 shadow-2xl shadow-cyan-500/10 rounded-2xl px-8 py-8 flex flex-col gap-5 w-full max-w-md animate-form">
            <h2 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-400 mb-2">Add Income</h2>
            
            <div class="flex flex-col gap-2">
                <label class="text-gray-400 text-sm ml-1">Amount</label>
                <div class="relative">
                    <i class="fa-solid fa-dollar-sign absolute left-4 top-3.5 text-gray-500"></i>
                    <input required type="text" name="income-amount" placeholder="0.00" class="w-full bg-slate-900 border border-white/10 rounded-xl py-3 pl-10 pr-4 text-white focus:outline-none focus:border-cyan-400 focus:shadow-[0_0_15px_rgba(34,211,238,0.2)] transition-all">
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-gray-400 text-sm ml-1">Description</label>
                <div class="relative">
                    <i class="fa-solid fa-tag absolute left-4 top-3.5 text-gray-500"></i>
                    <input required type="text" name="income-description" placeholder="e.g. Salary, Freelance" class="w-full bg-slate-900 border border-white/10 rounded-xl py-3 pl-10 pr-4 text-white focus:outline-none focus:border-cyan-400 focus:shadow-[0_0_15px_rgba(34,211,238,0.2)] transition-all">
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-gray-400 text-sm ml-1">Date</label>
                <div class="relative">
                    <input type="date" name="income-date" class="w-full bg-slate-900 border border-white/10 rounded-xl py-3 px-4 text-gray-400 focus:outline-none focus:border-cyan-400 transition-all">
                </div>
            </div>

            <input type="submit" name="income-submit" value="Save Income" class="mt-4 cursor-pointer bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-bold py-3 rounded-xl hover:shadow-[0_0_20px_rgba(6,182,212,0.4)] transition-all active:scale-95">
        </form>
    </div>

    <div class="expense-form-bg hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex justify-center items-center">
        <form action="index.php" method="post" class="relative bg-[#0f172a] border border-white/10 shadow-2xl shadow-fuchsia-500/10 rounded-2xl px-8 py-8 flex flex-col gap-5 w-full max-w-md animate-form">
            <h2 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-400 to-purple-400 mb-2">Add Expense</h2>
            
            <div class="flex flex-col gap-2">
                <label class="text-gray-400 text-sm ml-1">Amount</label>
                <div class="relative">
                    <i class="fa-solid fa-dollar-sign absolute left-4 top-3.5 text-gray-500"></i>
                    <input required type="text" name="expense-amount" placeholder="0.00" class="w-full bg-slate-900 border border-white/10 rounded-xl py-3 pl-10 pr-4 text-white focus:outline-none focus:border-fuchsia-400 focus:shadow-[0_0_15px_rgba(192,38,211,0.2)] transition-all">
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-gray-400 text-sm ml-1">Description</label>
                <div class="relative">
                    <i class="fa-solid fa-tag absolute left-4 top-3.5 text-gray-500"></i>
                    <input required type="text" name="expense-description" placeholder="e.g. Food, Rent" class="w-full bg-slate-900 border border-white/10 rounded-xl py-3 pl-10 pr-4 text-white focus:outline-none focus:border-fuchsia-400 focus:shadow-[0_0_15px_rgba(192,38,211,0.2)] transition-all">
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-gray-400 text-sm ml-1">Date</label>
                <div class="relative">
                    <input type="date" name="expense-date" class="w-full bg-slate-900 border border-white/10 rounded-xl py-3 px-4 text-gray-400 focus:outline-none focus:border-fuchsia-400 transition-all">
                </div>
            </div>

            <input type="submit" name="expense-submit" value="Save Expense" class="mt-4 cursor-pointer bg-gradient-to-r from-fuchsia-500 to-purple-600 text-white font-bold py-3 rounded-xl hover:shadow-[0_0_20px_rgba(192,38,211,0.4)] transition-all active:scale-95">
        </form>
    </div>
</body>
<script src="script.js"></script>

</html>
<?php
mysqli_close($connect);
?>