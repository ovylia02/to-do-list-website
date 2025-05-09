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
        <!--DASHBOARD-->
        <div class="min-h-screen flex flex-col items-center p-8">
            <!--application name-->
            <h1 class="text-4xl font-bold mb-10">🐹 Your To-Do List Companion 🐹</h1>

            <!--to-do list-->
            <div class="w-full max-w-3xl bg-[#D8F3DC] rounded-2xl shadow-xl p-6 relative h-[500px] flex flex-col">
                <!--add task button-->
                <div class="flex justify-end mb-4">
                    <button onclick="openCreatePopup()" class="bg-[#bde0fe] hover:bg-[#a2d2ff] font-semibold px-5 py-2 rounded-xl shadow">+ Add Task</button>
                </div>

                <!--task list area-->
                <div class="flex-grow overflow-y-auto space-y-5">
                    <!--if: no tasks-->
                    <?php if (empty($taskList)): ?>
                        <div class="text-center text-lg">No tasks available for now! 💤</div>

                    <!--if: have tasks-->
                    <?php else: ?>
                        <?php foreach($taskList as $task): ?>
                            <?php
                            // Priority color coding
                            $priority = $task['priority'];
                            $priorityCoding = ['least' => '#e9c46a', 'neutral' => '#f4a261', 'high' => '#e76f51'];
                            $priorityColor = $priorityCoding[$priority];
                            ?>

                            <!--each task-->
                            <div class="flex items-start gap-4">
                                <!--done checkbox-->
                                <input type="checkbox" class="mt-2 w-5 h-5 accent-green-500 shrink-0" />

                                <!--task box-->
                                <div onclick="openUpdatePopup()" class="w-full cursor-pointer flex items-center justify-between bg-white rounded-xl shadow p-4 border-l-8 hover:shadow-md transition" style="border-color: <?= $priorityColor ?>;">
                                    <!--task description-->
                                    <div class="flex items-center gap-3 text-lg font-medium w-full">
                                        <?= htmlspecialchars($task['task']) ?>
                                    </div>

                                    <!--priority category-->
                                    <span class="text-sm font-semibold px-3 py-1 rounded-full text-white" style="background-color: <?= $priorityColor ?>;">
                                        <?= ucfirst($priority) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!--CREATE POPUP BOX-->
        <div id="createPopupBox" class="hidden fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm flex items-center justify-center z-50">
            <form action="create.php" method="POST" class="bg-white rounded-2xl p-6 w-96 shadow-xl space-y-4">
                <!--create prompt-->
                <h2 class="text-2xl font-bold text-center">What do you wanna do?</h2>

                <!--input task-->
                <input name="createTask" type="text" placeholder="Enter your task here..." class="w-full p-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                
                <!--choose priority level-->
                <div class="space-y-1">
                    <label class="block font-medium">Priority level?</label>
                    <div name="createPriority" class="flex gap-4">
                        <label><input type="radio" name="createPriority" value="least" /> Least</label>
                        <label><input type="radio" name="createPriority" value="neutral" /> Neutral</label>
                        <label><input type="radio" name="createPriority" value="high" /> High</label>
                    </div>
                </div>

                <!--cancel and add button-->
                <div class="flex justify-end gap-4 pt-4">
                    <button type="button" onclick="closeCreatePopup()" class="px-4 py-2 rounded-xl bg-gray-300 hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#bde0fe] hover:bg-[#a2d2ff]">Add</button>
                </div>
            </form>
        </div>

        <!--UPDATE POPUP BOX-->
        <div id="updatePopupBox" class="hidden fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm flex items-center justify-center z-50">
            <form action="update.php" method="POST" class="bg-white rounded-2xl p-6 w-96 shadow-xl space-y-4">
                <!--update prompt-->
                <h2 class="text-2xl font-bold text-center">Update this task here!</h2>

                <!--edit task-->
                <input name="updateTask" type="text" class="w-full p-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-200" />
            
                <!--edit priority level-->
                <div class="space-y-1">
                    <label class="block font-medium">Change priority level?</label>
                    <div name="updatePriority" class="flex gap-4">
                        <label><input type="radio" name="updatePriority" value="least" /> Least</label>
                        <label><input type="radio" name="updatePriority" value="neutral" /> Neutral </label>
                        <label><input type="radio" name="updatePriority" value="high" /> High </label>
                    </div>
                </div>

                <!--cancel and update button-->
                <div class="flex justify-end gap-4 pt-4">
                    <button type="button" onclick="closeUpdatePopup()" class="px-4 py-2 rounded-xl bg-gray-300 hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#bde0fe] hover:bg-[#a2d2ff]">Update</button>
                </div>
            </form>
        </div>

        <!--JAVASCRIPT-->
        <script>
            // CREATE POPUP BOX JS
            function openCreatePopup() {
                document.getElementById("createPopupBox").classList.remove("hidden");
            }

            function closeCreatePopup() {
                document.getElementById("createPopupBox").classList.add("hidden");
            }

            // UPDATE POPUP BOX JS
            function openUpdatePopup() {
                document.getElementById("updatePopupBox").classList.remove("hidden");
            }

            function closeUpdatePopup() {
                document.getElementById("updatePopupBox").classList.add("hidden");
            }
        </script>
    </body>
</html>

<?php
// Disconnect from database
$connection->close();
?>
