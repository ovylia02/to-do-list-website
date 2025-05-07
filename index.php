<?php
// Connect to database
include 'db.php';

// READ functionality
$taskTable = $connection->query("SELECT * FROM tasks ORDER BY id DESC");
$taskList = [];
if ($taskTable && $taskTable->num_rows > 0) {
    while ($row = $taskTable->fetch_assoc()) {
        $taskList[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!--MAIN WEBSITE SETTINGS-->
        <title>To-Do List</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!--FONTS-->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet" />
    
        <!--UI-->
        <link rel="stylesheet" href="style.css" />
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body>        
        <!--MAIN SCREEN-->
        <div id="mainScreen" class="min-h-screen flex flex-col items-center p-8">
            <!--heading-->
            <h1 class="text-4xl font-bold mb-10">🐹 Your To-Do List Companion 🐹</h1>

            <!--task container-->
            <div class="w-full max-w-3xl bg-[#D8F3DC] rounded-2xl shadow-xl p-6 relative h-[500px] flex flex-col">
                <!--add button-->
                <div class="flex justify-end mb-4">
                    <button onclick="openCreatePopup()" class="bg-[#bde0fe] hover:bg-[#a2d2ff] font-semibold px-5 py-2 rounded-xl shadow">
                        + Add Task
                    </button>
                </div>

                <!--task list area-->
                <div class="flex-grow overflow-y-auto space-y-5">
                    <!--no tasks-->
                    <?php if (empty($taskList)): ?>
                        <div class="text-center text-lg">No tasks available for now! 💤</div>

                    <!--have tasks-->
                    <?php else: ?>
                        <?php foreach($taskList as $task): ?>
                            <?php
                            // Priority coding
                            $priority = $task['priority'];
                            $priorityCoding = ['least' => '#e9c46a', 'neutral' => '#f4a261', 'high' => '#e76f51'];
                            $priorityColor = $priorityCoding[$priority];
                            ?>

                            <div class="flex items-center justify-between bg-white rounded-xl shadow p-4 border-l-8" style="border-color: <?= $priorityColor ?>;">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" class="w-5 h-5 accent-green-500" />
                                    <div class="text-lg font-medium">
                                        <?= htmlspecialchars($task['task']) ?>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold px-3 py-1 rounded-full text-white" style="background-color: <?= $priorityColor ?>;">
                                    <?= ucfirst($priority) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!--CREATE POPUP BOX-->
        <div id="createPopupBox" class="hidden fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm flex items-center justify-center z-50">
            <form action="create.php" method="POST" class="bg-white rounded-2xl p-6 w-96 shadow-xl space-y-4">
                <!--prompt-->
                <h2 class="text-2xl font-bold text-center">
                    What do you wanna do?
                </h2>

                <!--input task-->
                <input name="task" type="text" placeholder="Enter your task here..." class="w-full p-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                
                <!--choose priority level-->
                <div class="space-y-1">
                    <label class="block font-medium">Priority level?</label>
                    <div name="priority" class="flex gap-4">
                        <label><input type="radio" name="priority" value="least" /> Least</label>
                        <label><input type="radio" name="priority" value="neutral" /> Neutral</label>
                        <label><input type="radio" name="priority" value="high" /> High</label>
                    </div>
                </div>

                <!--cancel button + submit button-->
                <div class="flex justify-end gap-4 pt-4">
                    <button type="button" onclick="closeCreatePopup()" class="px-4 py-2 rounded-xl bg-gray-300 hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#bde0fe] hover:bg-[#a2d2ff]">Add</button>
                </div>
            </form>
        </div>

        <!--JAVASCRIPT-->
        <script>
            // Open - Popup boxes
            function openCreatePopup() {
                document.getElementById("createPopupBox").classList.remove("hidden");
            }

            // Close - Popup boxes
            function closeCreatePopup() {
                document.getElementById("createPopupBox").classList.add("hidden");
            }
        </script>
    </body>
</html>

<?php
// Disconnect from database
$connection->close();
?>
