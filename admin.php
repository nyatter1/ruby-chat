<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruby Chat - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { colors: { gray: { 850: '#1f2937', 900: '#111827', 950: '#030712' }, ruby: { 500: '#e0115f', 600: '#c20e50' } } } }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-950 text-gray-100 font-sans min-h-screen">

    <!-- Auth Protection Overlay -->
    <div id="auth-check" class="fixed inset-0 z-50 bg-black flex flex-col items-center justify-center">
        <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-ruby-500 mb-4"></div>
        <h2 class="text-xl font-bold">Verifying Credentials...</h2>
    </div>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-900 border-r border-gray-800 flex flex-col">
            <div class="p-6 border-b border-gray-800">
                <h1 class="text-2xl font-bold tracking-tight"><span class="text-ruby-500">Ruby</span>Admin</h1>
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <button onclick="switchTab('dashboard')" id="nav-dashboard" class="w-full text-left px-4 py-3 rounded-lg bg-ruby-500/10 text-ruby-500 font-bold border border-ruby-500/20"><i class="fas fa-chart-pie w-6"></i> Dashboard</button>
                <button onclick="switchTab('users')" id="nav-users" class="w-full text-left px-4 py-3 rounded-lg hover:bg-gray-800 text-gray-400 font-bold transition-colors"><i class="fas fa-users w-6"></i> User Management</button>
                <button onclick="switchTab('system')" id="nav-system" class="w-full text-left px-4 py-3 rounded-lg hover:bg-gray-800 text-gray-400 font-bold transition-colors"><i class="fas fa-cogs w-6"></i> System Actions</button>
            </nav>
            <div class="p-4 border-t border-gray-800">
                <button onclick="window.location.href='index.html'" class="w-full bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 rounded transition-colors"><i class="fas fa-arrow-left mr-2"></i> Back to Chat</button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto bg-gray-950 p-8">
            
            <!-- Dashboard -->
            <div id="tab-dashboard" class="space-y-6">
                <h2 class="text-3xl font-bold mb-6">Overview</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-900 p-6 rounded-xl border border-gray-800 shadow-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="text-sm text-gray-400 uppercase font-bold">Total Users</div>
                                <div class="text-4xl font-bold text-white mt-2" id="stat-total-users">0</div>
                            </div>
                            <div class="p-3 bg-blue-500/10 rounded-lg text-blue-500"><i class="fas fa-users text-2xl"></i></div>
                        </div>
                    </div>
                    <div class="bg-gray-900 p-6 rounded-xl border border-gray-800 shadow-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="text-sm text-gray-400 uppercase font-bold">Online Now</div>
                                <div class="text-4xl font-bold text-green-500 mt-2" id="stat-online-users">0</div>
                            </div>
                            <div class="p-3 bg-green-500/10 rounded-lg text-green-500"><i class="fas fa-globe text-2xl"></i></div>
                        </div>
                    </div>
                    <div class="bg-gray-900 p-6 rounded-xl border border-gray-800 shadow-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="text-sm text-gray-400 uppercase font-bold">Banned Users</div>
                                <div class="text-4xl font-bold text-red-500 mt-2" id="stat-banned-users">0</div>
                            </div>
                            <div class="p-3 bg-red-500/10 rounded-lg text-red-500"><i class="fas fa-ban text-2xl"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Management -->
            <div id="tab-users" class="hidden space-y-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-3xl font-bold">User Management</h2>
                    <div class="relative">
                        <input type="text" id="user-search" placeholder="Search by name, email, UID..." class="bg-gray-900 border border-gray-700 rounded-lg pl-10 pr-4 py-2 w-80 text-white focus:border-ruby-500 focus:outline-none">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-500"></i>
                    </div>
                </div>

                <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden shadow-lg">
                    <table class="w-full text-left">
                        <thead class="bg-gray-850 text-gray-400 text-xs uppercase font-bold">
                            <tr>
                                <th class="p-4">User</th>
                                <th class="p-4">Role</th>
                                <th class="p-4">Status</th>
                                <th class="p-4">Wallet</th>
                                <th class="p-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="users-table-body" class="divide-y divide-gray-800 text-sm">
                            <!-- Injected JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- System Actions -->
            <div id="tab-system" class="hidden space-y-6">
                <h2 class="text-3xl font-bold">System Actions</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Broadcast -->
                    <div class="bg-gray-900 p-6 rounded-xl border border-gray-800">
                        <h3 class="text-xl font-bold mb-4 flex items-center gap-2"><i class="fas fa-bullhorn text-ruby-500"></i> Global Broadcast</h3>
                        <p class="text-gray-400 mb-4 text-sm">Send a system alert to all users currently in the public chat.</p>
                        <textarea id="broadcast-msg" class="w-full bg-gray-950 border border-gray-700 rounded p-3 text-white mb-4 focus:border-ruby-500 focus:outline-none" rows="3" placeholder="Announcement text..."></textarea>
                        <button onclick="sendSystemBroadcast()" class="w-full bg-ruby-500 hover:bg-ruby-600 text-white font-bold py-2 rounded transition-colors">Send Broadcast</button>
                    </div>

                    <!-- Chat Control -->
                    <div class="bg-gray-900 p-6 rounded-xl border border-gray-800">
                        <h3 class="text-xl font-bold mb-4 flex items-center gap-2"><i class="fas fa-trash-alt text-red-500"></i> Chat Moderation</h3>
                        <div class="space-y-4">
                            <button onclick="nukeChat()" class="w-full bg-red-900/30 hover:bg-red-900/50 border border-red-800 text-red-400 font-bold py-3 rounded transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-bomb"></i> Clear Last 50 Messages
                            </button>
                            <div class="text-xs text-gray-500 text-center">Caution: This action cannot be undone.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="edit-user-modal" class="fixed inset-0 z-50 bg-black/80 hidden flex items-center justify-center p-4">
        <div class="bg-gray-900 border border-gray-700 rounded-xl p-6 w-full max-w-lg">
            <h3 class="text-xl font-bold mb-4">Edit User</h3>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs text-gray-400 uppercase font-bold mb-1">Rank</label>
                    <select id="edit-rank" class="w-full bg-gray-950 border border-gray-700 p-2 rounded text-white"></select>
                </div>
                <div>
                    <label class="block text-xs text-gray-400 uppercase font-bold mb-1">Coins</label>
                    <input type="number" id="edit-coins" class="w-full bg-gray-950 border border-gray-700 p-2 rounded text-white">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-xs text-gray-400 uppercase font-bold mb-1">Gems</label>
                    <input type="number" id="edit-gems" class="w-full bg-gray-950 border border-gray-700 p-2 rounded text-white">
                </div>
            </div>
            <div class="flex gap-3">
                <button onclick="saveUserEdits()" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-2 rounded">Save Changes</button>
                <button onclick="document.getElementById('edit-user-modal').classList.add('hidden')" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 rounded">Cancel</button>
            </div>
        </div>
    </div>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-app.js";
        import { getAuth, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-auth.js";
        import { getFirestore, collection, doc, getDoc, updateDoc, query, orderBy, onSnapshot, addDoc, serverTimestamp, getDocs, limit, deleteDoc, enableIndexedDbPersistence } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-firestore.js";

        const firebaseConfig = {
            apiKey: "AIzaSyC-Ti6kJB_bgdeC0I_1Pt9Bl3XZp7pTjoU",
            authDomain: "cartoony-chat.firebaseapp.com",
            databaseURL: "https://cartoony-chat-default-rtdb.europe-west1.firebasedatabase.app",
            projectId: "cartoony-chat",
            storageBucket: "cartoony-chat.firebasestorage.app",
            messagingSenderId: "827018960215",
            appId: "1:827018960215:web:0e0c2436c9c4e1dceb7212",
            measurementId: "G-HF16NJSQ16"
        };

        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);
        const db = getFirestore(app);
        
        // Attempt persistence
        try { enableIndexedDbPersistence(db).catch(err => { if(err.code == 'failed-precondition') {} else if(err.code == 'unimplemented') {} }); } catch(e){}

        let currentUser = null;
        let allUsers = [];
        let editingUserId = null;

        const RANKS = ["Developer","Founder","CEO","Head Owner","Owner","Manager","Super Admin","Admin","Moderator","Trial Mod","Staff","VIP"];

        onAuthStateChanged(auth, async (user) => {
            if (user) {
                const snap = await getDoc(doc(db, "users", user.uid));
                if (snap.exists()) {
                    const data = snap.data();
                    const rank = data.rank || "VIP";
                    if (["Developer", "Founder", "CEO", "Head Owner", "Owner", "Manager", "Super Admin", "Admin"].includes(rank)) {
                        currentUser = user;
                        document.getElementById('auth-check').classList.add('hidden');
                        initDashboard();
                    } else {
                        alert("Access Denied: Insufficient Permissions");
                        window.location.href = 'index.html';
                    }
                } else window.location.href = 'index.html';
            } else window.location.href = 'index.html';
        });

        function initDashboard() {
            onSnapshot(collection(db, "users"), (snapshot) => {
                allUsers = [];
                let online = 0, banned = 0;
                const now = new Date();
                snapshot.forEach(doc => {
                    const d = doc.data();
                    const isOnline = d.lastSeen && (now - d.lastSeen.toDate() < 300000);
                    if(isOnline) online++;
                    if(d.banned) banned++;
                    allUsers.push({ id: doc.id, ...d, isOnline });
                });
                
                document.getElementById('stat-total-users').innerText = allUsers.length;
                document.getElementById('stat-online-users').innerText = online;
                document.getElementById('stat-banned-users').innerText = banned;
                renderUsersTable();
            });

            const rankSel = document.getElementById('edit-rank');
            rankSel.innerHTML = RANKS.map(r => `<option value="${r}">${r}</option>`).join('');
        }

        window.renderUsersTable = () => {
            const term = document.getElementById('user-search').value.toLowerCase();
            const filtered = allUsers.filter(u => (u.username||"").toLowerCase().includes(term) || (u.email||"").toLowerCase().includes(term) || u.id === term);
            
            document.getElementById('users-table-body').innerHTML = filtered.map(u => `
                <tr class="hover:bg-gray-800 transition-colors border-b border-gray-800">
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <img src="${u.photoURL}" class="w-8 h-8 rounded-full bg-gray-700">
                            <div>
                                <div class="font-bold text-white">${u.username}</div>
                                <div class="text-xs text-gray-500">${u.email}</div>
                                <div class="text-[10px] text-gray-600 font-mono">${u.id}</div>
                            </div>
                        </div>
                    </td>
                    <td class="p-4"><span class="bg-gray-800 border border-gray-700 px-2 py-1 rounded text-xs text-white">${u.rank}</span></td>
                    <td class="p-4">
                        ${u.banned ? '<span class="text-red-500 font-bold text-xs">BANNED</span>' : (u.isOnline ? '<span class="text-green-500 font-bold text-xs">ONLINE</span>' : '<span class="text-gray-500 text-xs">OFFLINE</span>')}
                    </td>
                    <td class="p-4 text-xs text-gray-400">
                        <div><i class="fas fa-coins text-yellow-500 mr-1"></i> ${u.coins||0}</div>
                        <div><i class="fas fa-gem text-ruby-500 mr-1"></i> ${u.gems||0}</div>
                    </td>
                    <td class="p-4 text-right">
                        <button onclick="openEditUser('${u.id}')" class="p-2 bg-blue-600/20 text-blue-400 hover:bg-blue-600 hover:text-white rounded transition-colors mr-2"><i class="fas fa-edit"></i></button>
                        ${!u.banned ? `<button onclick="quickAction('${u.id}', 'ban')" class="p-2 bg-red-600/20 text-red-400 hover:bg-red-600 hover:text-white rounded transition-colors" title="Ban"><i class="fas fa-ban"></i></button>` : `<button onclick="quickAction('${u.id}', 'unban')" class="p-2 bg-green-600/20 text-green-400 hover:bg-green-600 hover:text-white rounded transition-colors" title="Unban"><i class="fas fa-check"></i></button>`}
                    </td>
                </tr>
            `).join('');
        };

        window.openEditUser = (uid) => {
            const u = allUsers.find(x => x.id === uid);
            if(!u) return;
            editingUserId = uid;
            document.getElementById('edit-rank').value = u.rank;
            document.getElementById('edit-coins').value = u.coins || 0;
            document.getElementById('edit-gems').value = u.gems || 0;
            document.getElementById('edit-user-modal').classList.remove('hidden');
        };

        window.saveUserEdits = async () => {
            if(!editingUserId) return;
            const rank = document.getElementById('edit-rank').value;
            const coins = parseInt(document.getElementById('edit-coins').value);
            const gems = parseInt(document.getElementById('edit-gems').value);
            await updateDoc(doc(db, "users", editingUserId), { rank, coins, gems });
            document.getElementById('edit-user-modal').classList.add('hidden');
        };

        window.quickAction = async (uid, action) => {
            if(!confirm("Are you sure?")) return;
            if(action === 'ban') {
                await updateDoc(doc(db, "users", uid), { banned: true, banReason: "Admin Action" });
            } else if (action === 'unban') {
                await updateDoc(doc(db, "users", uid), { banned: false });
            }
        };

        window.sendSystemBroadcast = async () => {
            const txt = document.getElementById('broadcast-msg').value;
            if(!txt) return;
            await addDoc(collection(db, "messages"), { text: txt, isSystem: true, timestamp: serverTimestamp() });
            alert("Sent."); document.getElementById('broadcast-msg').value = '';
        };

        window.nukeChat = async () => {
            if(!confirm("Delete last 50 messages?")) return;
            const q = query(collection(db, "messages"), orderBy("timestamp", "desc"), limit(50));
            const s = await getDocs(q);
            s.forEach(async d => await deleteDoc(d.ref));
            alert("Deleted.");
        };

        window.switchTab = (tab) => {
            ['dashboard','users','system'].forEach(t => {
                document.getElementById(`tab-${t}`).classList.add('hidden');
                document.getElementById(`nav-${t}`).className = "w-full text-left px-4 py-3 rounded-lg hover:bg-gray-800 text-gray-400 font-bold transition-colors";
            });
            document.getElementById(`tab-${tab}`).classList.remove('hidden');
            document.getElementById(`nav-${tab}`).className = "w-full text-left px-4 py-3 rounded-lg bg-ruby-500/10 text-ruby-500 font-bold border border-ruby-500/20";
        };
        
        document.getElementById('user-search').addEventListener('input', renderUsersTable);
    </script>
</body>
</html>
