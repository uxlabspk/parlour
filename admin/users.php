<?php
// php_web/admin/users.php
include __DIR__ . '/../includes/header.php';
requireAdmin();

$action = $_GET['action'] ?? 'list';
$error = '';
$success = '';

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user'])) {
        $email = trim($_POST['email']);
        $name = trim($_POST['name']);
        $password = $_POST['password'];
        $role = $_POST['role'];

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email address.';
        } else {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'Email already exists.';
            } else {
                $id = generateUUID();
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (id, email, name, password, role, emailVerified) VALUES (?, ?, ?, ?, ?, TRUE)");
                try {
                    $stmt->execute([$id, $email, $name, $hashedPassword, $role]);
                    $success = 'User added successfully!';
                    $action = 'list';
                } catch (Exception $e) {
                    $error = 'Error adding user: ' . $e->getMessage();
                }
            }
        }
    } elseif (isset($_POST['update_user'])) {
        $id = $_POST['user_id'];
        $email = trim($_POST['email']);
        $name = trim($_POST['name']);
        $role = $_POST['role'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email address.';
        } else {
            // Check if email exists for other users
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $id]);
            if ($stmt->fetch()) {
                $error = 'Email already exists.';
            } else {
                // Update user info
                $stmt = $pdo->prepare("UPDATE users SET email = ?, name = ?, role = ? WHERE id = ?");
                try {
                    $stmt->execute([$email, $name, $role, $id]);
                    
                    // Update password if provided
                    if (!empty($_POST['password'])) {
                        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                        $stmt->execute([$hashedPassword, $id]);
                    }
                    
                    $success = 'User updated successfully!';
                    $action = 'list';
                } catch (Exception $e) {
                    $error = 'Error updating user: ' . $e->getMessage();
                }
            }
        }
    }
}

// Handle delete action
if ($action === 'delete') {
    $id = $_GET['id'];
    // Prevent deleting yourself
    if ($id === $_SESSION['userId']) {
        $error = 'You cannot delete your own account.';
        $action = 'list';
    } else {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $success = 'User deleted successfully!';
        $action = 'list';
    }
}

// Fetch user for editing
$editUser = null;
if ($action === 'edit') {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $editUser = $stmt->fetch();
    if (!$editUser) {
        $error = 'User not found.';
        $action = 'list';
    }
}

// Fetch all users with their order stats
$stmt = $pdo->query("
    SELECT 
        u.*, 
        COUNT(DISTINCT o.id) as orderCount,
        COALESCE(SUM(o.total), 0) as totalSpent
    FROM users u
    LEFT JOIN orders o ON u.id = o.userId
    GROUP BY u.id
    ORDER BY u.createdAt DESC
");
$users = $stmt->fetchAll();
?>

<div class="">
    <!-- Admin Header -->
    <div class="sm:pt-36 pt-24 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <a href="https://parlour.com/admin" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-white transition-colors mb-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Dashboard
                    </a>
                    <h1 class="text-3xl lg:text-4xl font-light">
                        User <span class="font-semibold">Management</span>
                    </h1>
                </div>
                <button onclick="document.getElementById('add-modal').classList.remove('hidden')" class="inline-flex items-center gap-2 bg-white text-gray-900 px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-gray-100 transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add User
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 -mt-6">
        <?php if ($success): ?>
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Users Table -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">All Users <span class="text-gray-400 font-normal">(<?php echo count($users); ?>)</span></h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Orders</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Spent</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gray-900 flex items-center justify-center text-white font-semibold text-sm">
                                            <?php echo strtoupper(substr($user['name'] ?? $user['email'], 0, 1)); ?>
                                        </div>
                                        <span class="font-medium text-gray-900"><?php echo htmlspecialchars($user['name'] ?? 'N/A'); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td class="px-6 py-4">
                                    <?php 
                                    $roleColors = [
                                        'ADMIN' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'MANAGER' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'USER' => 'bg-gray-50 text-gray-700 border-gray-200'
                                    ];
                                    $roleColor = $roleColors[$user['role']] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border <?php echo $roleColor; ?>">
                                        <?php echo $user['role']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?php echo $user['orderCount']; ?></td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">PKR <?php echo number_format($user['totalSpent'], 0); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?php echo date('M d, Y', strtotime($user['createdAt'])); ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <a href="https://parlour.com/admin/users?action=edit&id=<?php echo $user['id']; ?>" class="text-gray-600 hover:text-gray-900 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <?php if ($user['id'] !== $_SESSION['userId']): ?>
                                            <a href="https://parlour.com/admin/users?action=delete&id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?')" class="text-red-500 hover:text-red-700 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <br>
</div>

<!-- Add User Modal -->
<div id="add-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl p-8 max-w-lg w-full mx-4 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Add New User</h2>
            <button onclick="document.getElementById('add-modal').classList.add('hidden')" class="p-2 hover:bg-gray-100 rounded-xl transition-colors">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" class="space-y-5">
            <input type="hidden" name="add_user" value="1">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <div class="relative">
                    <input id="add_password" type="password" name="password" required minlength="6" class="w-full px-4 pr-12 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200">
                    <button type="button" id="toggleAddPassword" aria-label="Toggle password visibility" class="absolute top-1/2 transform -translate-y-1/2 right-3 text-gray-500 hover:text-gray-900">
                        <svg id="eyeOpenAdd" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="eyeClosedAdd" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.223-3.452M6.1 6.1A9.953 9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.13 5.9M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                <p class="text-xs text-gray-400 mt-1">Minimum 6 characters</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select name="role" required class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200">
                    <option value="USER">User</option>
                    <option value="MANAGER">Manager</option>
                    <option value="ADMIN">Admin</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('add-modal').classList.add('hidden')" class="flex-1 bg-gray-100 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-colors duration-200">Cancel</button>
                <button type="submit" class="flex-1 bg-gray-900 text-white py-3 rounded-xl font-semibold hover:bg-gray-800 transition-colors duration-200">Add User</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<?php if ($editUser): ?>
<div id="edit-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] flex items-center justify-center">
    <div class="bg-white rounded-2xl p-8 max-w-lg w-full mx-4 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Edit User</h2>
            <a href="https://parlour.com/admin/users" class="p-2 hover:bg-gray-100 rounded-xl transition-colors">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
        </div>
        <form method="POST" class="space-y-5">
            <input type="hidden" name="update_user" value="1">
            <input type="hidden" name="user_id" value="<?php echo $editUser['id']; ?>">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($editUser['name'] ?? ''); ?>" required class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($editUser['email']); ?>" required class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                <div class="relative">
                    <input id="edit_password" type="password" name="password" minlength="6" class="w-full px-4 pr-12 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200" placeholder="Leave blank to keep current">
                    <button type="button" id="toggleEditPassword" aria-label="Toggle password visibility" class="absolute top-1/2 transform -translate-y-1/2 right-3 text-gray-500 hover:text-gray-900">
                        <svg id="eyeOpenEdit" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="eyeClosedEdit" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.223-3.452M6.1 6.1A9.953 9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.13 5.9M3 3l18 18" />
                        </svg>
                    </button>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select name="role" required class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-100 transition-all duration-200">
                    <option value="USER" <?php echo $editUser['role'] === 'USER' ? 'selected' : ''; ?>>User</option>
                    <option value="MANAGER" <?php echo $editUser['role'] === 'MANAGER' ? 'selected' : ''; ?>>Manager</option>
                    <option value="ADMIN" <?php echo $editUser['role'] === 'ADMIN' ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <a href="https://parlour.com/admin/users" class="flex-1 text-center bg-gray-100 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-colors duration-200">Cancel</a>
                <button type="submit" class="flex-1 bg-gray-900 text-white py-3 rounded-xl font-semibold hover:bg-gray-800 transition-colors duration-200">Update User</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function setupToggle(toggleId, inputId, eyeOpenId, eyeClosedId) {
        var toggle = document.getElementById(toggleId);
        var input = document.getElementById(inputId);
        var eyeOpen = document.getElementById(eyeOpenId);
        var eyeClosed = document.getElementById(eyeClosedId);
        if (!toggle || !input) return;
        toggle.addEventListener('click', function() {
            if (input.type === 'password') {
                input.type = 'text';
                if (eyeOpen) eyeOpen.classList.add('hidden');
                if (eyeClosed) eyeClosed.classList.remove('hidden');
                toggle.setAttribute('aria-pressed', 'true');
            } else {
                input.type = 'password';
                if (eyeOpen) eyeOpen.classList.remove('hidden');
                if (eyeClosed) eyeClosed.classList.add('hidden');
                toggle.setAttribute('aria-pressed', 'false');
            }
        });
    }

    setupToggle('toggleAddPassword', 'add_password', 'eyeOpenAdd', 'eyeClosedAdd');
    setupToggle('toggleEditPassword', 'edit_password', 'eyeOpenEdit', 'eyeClosedEdit');
});
</script>
