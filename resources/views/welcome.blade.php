<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InstaClone K3 — Social Media PBO</title>
    <style>
        :root {
            --bg: #0f172a;
            --card: #1e293b;
            --border: #334155;
            --text: #f8fafc;
            --muted: #94a3b8;
            --primary: #38bdf8;
            --accent: #f43f5e;
            --success: #22c55e;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', system-ui, sans-serif; }
        body { background: var(--bg); color: var(--text); min-height: 100vh; padding-bottom: 60px; }
        header {
            background: rgba(30, 41, 59, 0.95); border-bottom: 1px solid var(--border);
            padding: 14px 24px; position: sticky; top: 0; z-index: 50;
            display: flex; justify-content: space-between; align-items: center;
        }
        .brand { font-size: 1.25rem; font-weight: 800; color: var(--primary); letter-spacing: -0.5px; }
        .container { max-width: 920px; margin: 24px auto; padding: 0 16px; display: grid; grid-template-columns: 1fr 310px; gap: 20px; }
        @media (max-width: 768px) { .container { grid-template-columns: 1fr; } }
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 18px; margin-bottom: 16px; }
        h2, h3 { margin-bottom: 12px; font-size: 1.05rem; }
        input, textarea, select {
            width: 100%; padding: 10px 12px; margin-bottom: 10px; border-radius: 8px;
            border: 1px solid var(--border); background: #0b1120; color: var(--text); font-size: 0.92rem;
        }
        button {
            background: var(--primary); color: #0f172a; border: none; padding: 9px 15px;
            border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 0.88rem;
        }
        button.secondary { background: #334155; color: var(--text); }
        button.danger { background: var(--accent); color: #fff; }
        .tabs { display: flex; gap: 8px; margin-bottom: 12px; }
        .post-item { border-bottom: 1px solid var(--border); padding-bottom: 14px; margin-bottom: 14px; }
        .post-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .badge { background: #334155; color: var(--primary); padding: 2px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
        .actions { display: flex; gap: 10px; align-items: center; margin-top: 10px; flex-wrap: wrap; }
        .poll-opt {
            display: flex; justify-content: space-between; align-items: center;
            background: #0b1120; border: 1px solid var(--border); padding: 8px 12px;
            border-radius: 8px; margin-top: 6px; cursor: pointer;
        }
        .poll-opt:hover { border-color: var(--primary); }
        .comment-box { background: #0b1120; padding: 10px; border-radius: 8px; margin-top: 8px; font-size: 0.86rem; }
        .alert { padding: 10px 14px; border-radius: 8px; margin-bottom: 14px; font-size: 0.9rem; display: none; }
        .hidden { display: none !important; }
        .media-preview img, .media-preview video { max-width: 100%; border-radius: 8px; margin-top: 8px; max-height: 360px; }
    </style>
</head>
<body>
    <header>
        <div class="brand">InstaClone K3 — PBO Laravel 13</div>
        <div id="navUser" class="hidden" style="display:flex; gap:10px; align-items:center;">
            <span id="currentUserLabel" style="font-size:0.9rem; color:var(--muted);"></span>
            <button class="secondary" onclick="loadAll()">Refresh</button>
            <button class="danger" onclick="logout()">Logout</button>
        </div>
    </header>

    <div style="max-width: 920px; margin: 16px auto 0; padding: 0 16px;">
        <div id="alertBox" class="alert"></div>
    </div>

    <!-- AUTH VIEW -->
    <div id="authSection" style="max-width: 430px; margin: 40px auto; padding: 0 16px;">
        <div class="card">
            <div class="tabs">
                <button id="tabLoginBtn" onclick="switchAuthTab('login')">Login</button>
                <button id="tabRegBtn" class="secondary" onclick="switchAuthTab('register')">Register</button>
            </div>

            <div id="loginForm">
                <h3>Masuk ke Akun</h3>
                <input type="email" id="loginEmail" placeholder="Email (contoh: demo@untan.ac.id)">
                <input type="password" id="loginPass" placeholder="Password">
                <button style="width:100%" onclick="login()">Login Sekarang</button>
            </div>

            <div id="registerForm" class="hidden">
                <h3>Daftar Akun Baru</h3>
                <input type="text" id="regName" placeholder="Nama Lengkap">
                <input type="text" id="regUsername" placeholder="Username (tanpa spasi)">
                <input type="email" id="regEmail" placeholder="Email">
                <input type="password" id="regPass" placeholder="Password (min. 8 karakter)">
                <input type="password" id="regPassConf" placeholder="Konfirmasi Password">
                <button style="width:100%" onclick="register()">Buat Akun</button>
            </div>
        </div>
    </div>

    <!-- MAIN APP VIEW -->
    <div id="appSection" class="container hidden">
        <div>
            <!-- BUAT POSTINGAN (INHERITANCE: TEXT / MEDIA / POLL) -->
            <div class="card">
                <h3>Buat Postingan Baru (OOP Inheritance)</h3>
                <select id="postType" onchange="togglePostFields()">
                    <option value="text">TextPost (Khusus Teks)</option>
                    <option value="media">MediaPost (Upload Gambar/Video)</option>
                    <option value="poll">PollPost (Polling Voting)</option>
                </select>
                <textarea id="postCaption" rows="3" placeholder="Tulis caption atau pertanyaan polling..."></textarea>

                <div id="mediaField" class="hidden">
                    <input type="file" id="postMedia" multiple accept="image/jpeg,image/png,image/webp,video/mp4">
                </div>

                <div id="pollField" class="hidden">
                    <input type="text" id="pollOpt1" placeholder="Opsi Polling 1">
                    <input type="text" id="pollOpt2" placeholder="Opsi Polling 2">
                    <input type="text" id="pollOpt3" placeholder="Opsi Polling 3 (Opsional)">
                </div>

                <button onclick="createPost()">Publikasikan Postingan</button>
            </div>

            <!-- FEED TIMELINE -->
            <div class="card">
                <h3>Feed Timeline (Postingan & Interaksi)</h3>
                <div id="feedContainer">Memuat feed...</div>
            </div>
        </div>

        <div>
            <!-- PROFIL & CARI USER -->
            <div class="card">
                <h3>Cari & Follow Pengguna</h3>
                <div style="display:flex; gap:6px;">
                    <input type="text" id="searchQuery" placeholder="Ketik username / nama...">
                    <button onclick="searchUsers()">Cari</button>
                </div>
                <div id="searchResults" style="margin-top:8px; font-size:0.88rem;"></div>
            </div>

            <!-- NOTIFIKASI POLYMORPHIC -->
            <div class="card">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                    <h3 style="margin:0;">Notifikasi</h3>
                    <button class="secondary" style="padding:4px 8px; font-size:0.75rem;" onclick="markAllNotificationsRead()">Tandai Dibaca</button>
                </div>
                <div id="notifContainer" style="font-size:0.85rem; color:var(--muted);">Belum ada notifikasi.</div>
            </div>
        </div>
    </div>

    <script>
        let token = localStorage.getItem('instaclone_token') || '';
        let currentUser = null;

        function showAlert(msg, isError = false) {
            const box = document.getElementById('alertBox');
            box.style.display = 'block';
            box.style.background = isError ? '#7f1d1d' : '#14532d';
            box.style.color = '#fff';
            box.textContent = msg;
            setTimeout(() => { box.style.display = 'none'; }, 4000);
        }

        async function api(path, options = {}) {
            const headers = options.headers || {};
            headers['Accept'] = 'application/json';
            if (token) headers['Authorization'] = 'Bearer ' + token;
            if (!(options.body instanceof FormData)) {
                headers['Content-Type'] = 'application/json';
            }

            const res = await fetch('/api/v1' + path, { ...options, headers });
            const data = await res.json().catch(() => ({}));
            if (!res.ok) throw new Error(data.message || 'Terjadi kesalahan (' + res.status + ')');
            return data;
        }

        function switchAuthTab(mode) {
            document.getElementById('loginForm').classList.toggle('hidden', mode !== 'login');
            document.getElementById('registerForm').classList.toggle('hidden', mode !== 'register');
            document.getElementById('tabLoginBtn').className = mode === 'login' ? '' : 'secondary';
            document.getElementById('tabRegBtn').className = mode === 'register' ? '' : 'secondary';
        }

        function togglePostFields() {
            const t = document.getElementById('postType').value;
            document.getElementById('mediaField').classList.toggle('hidden', t !== 'media');
            document.getElementById('pollField').classList.toggle('hidden', t !== 'poll');
        }

        async function register() {
            try {
                const res = await api('/auth/register', {
                    method: 'POST',
                    body: JSON.stringify({
                        name: document.getElementById('regName').value,
                        username: document.getElementById('regUsername').value,
                        email: document.getElementById('regEmail').value,
                        password: document.getElementById('regPass').value,
                        password_confirmation: document.getElementById('regPassConf').value,
                    })
                });
                token = res.data.access_token;
                localStorage.setItem('instaclone_token', token);
                showAlert('Registrasi berhasil!');
                await initApp();
            } catch (e) { showAlert(e.message, true); }
        }

        async function login() {
            try {
                const res = await api('/auth/login', {
                    method: 'POST',
                    body: JSON.stringify({
                        email: document.getElementById('loginEmail').value,
                        password: document.getElementById('loginPass').value,
                    })
                });
                token = res.data.access_token;
                localStorage.setItem('instaclone_token', token);
                showAlert('Login berhasil!');
                await initApp();
            } catch (e) { showAlert(e.message, true); }
        }

        async function logout() {
            try { await api('/auth/logout', { method: 'POST' }); } catch (e) {}
            token = '';
            localStorage.removeItem('instaclone_token');
            location.reload();
        }

        async function createPost() {
            try {
                const type = document.getElementById('postType').value;
                const caption = document.getElementById('postCaption').value;
                const fd = new FormData();
                fd.append('type', type);
                if (caption) fd.append('caption', caption);

                if (type === 'media') {
                    const files = document.getElementById('postMedia').files;
                    for (let i = 0; i < files.length; i++) fd.append('media[]', files[i]);
                } else if (type === 'poll') {
                    ['pollOpt1', 'pollOpt2', 'pollOpt3'].forEach(id => {
                        const v = document.getElementById(id).value.trim();
                        if (v) fd.append('options[]', v);
                    });
                }

                await api('/posts', { method: 'POST', body: fd });
                document.getElementById('postCaption').value = '';
                showAlert('Postingan berhasil dipublikasikan!');
                await loadFeed();
            } catch (e) { showAlert(e.message, true); }
        }

        async function toggleReaction(postId, hasLiked, type = 'like') {
            try {
                if (hasLiked) {
                    await api(`/posts/${postId}/reactions`, { method: 'DELETE' });
                    showAlert('Reaksi dihapus (batal like).');
                } else {
                    await api(`/posts/${postId}/reactions`, {
                        method: 'POST',
                        body: JSON.stringify({ type })
                    });
                    showAlert('Berhasil memberi reaksi (' + type + ')!');
                }
                await loadFeed();
            } catch (e) { showAlert(e.message, true); }
        }

        async function votePoll(postId, optionId) {
            try {
                await api(`/posts/${postId}/vote`, {
                    method: 'POST',
                    body: JSON.stringify({ poll_option_id: optionId })
                });
                showAlert('Suara voting berhasil disimpan!');
                await loadFeed();
            } catch (e) { showAlert(e.message, true); }
        }

        async function deletePost(postId) {
            try {
                await api(`/posts/${postId}`, { method: 'DELETE' });
                showAlert('Postingan beserta seluruh reaksi & komentar berhasil dihapus dari MySQL!');
                await loadFeed();
            } catch (e) { showAlert(e.message, true); }
        }

        async function sendComment(postId) {
            const input = document.getElementById('commentInput-' + postId);
            if (!input || !input.value.trim()) return;
            try {
                await api(`/posts/${postId}/comments`, {
                    method: 'POST',
                    body: JSON.stringify({ body: input.value.trim() })
                });
                input.value = '';
                await loadFeed();
            } catch (e) { showAlert(e.message, true); }
        }

        async function loadFeed() {
            const box = document.getElementById('feedContainer');
            try {
                const res = await api('/feed');
                const posts = Array.isArray(res.data) ? res.data : (res.data?.data || []);

                if (!posts.length) {
                    box.innerHTML = '<p style="color:var(--muted);">Belum ada postingan di feed. Buat postingan pertamamu di atas!</p>';
                    return;
                }

                let html = '';
                for (const p of posts) {
                    const isMine = currentUser && p.author && p.author.id === currentUser.id;
                    const likedByMe = Boolean(p.is_reacted_by_me);

                    html += `<div class="post-item">
                        <div class="post-header">
                            <div><strong>@${p.author?.username || 'user'}</strong> <span style="color:var(--muted); font-size:0.82rem;">(${p.author?.name || ''})</span></div>
                            <span class="badge">${p.type}</span>
                        </div>
                        <p style="margin:6px 0; white-space:pre-wrap;">${p.caption || ''}</p>`;

                    if (p.media && p.media.length) {
                        html += `<div class="media-preview">`;
                        p.media.forEach(m => {
                            if (m.media_type === 'video') html += `<video controls src="${m.url}"></video>`;
                            else html += `<img src="${m.url}" alt="media">`;
                        });
                        html += `</div>`;
                    }

                    if (p.type === 'poll' && p.poll_options) {
                        p.poll_options.forEach(opt => {
                            html += `<div class="poll-opt" onclick="votePoll(${p.id}, ${opt.id})">
                                <span>${opt.option_text}</span>
                                <strong>${opt.votes_count} suara</strong>
                            </div>`;
                        });
                    }

                    html += `<div class="actions">
                        <button class="${likedByMe ? 'danger' : 'secondary'}" onclick="toggleReaction(${p.id}, ${likedByMe}, 'like')">
                            ${likedByMe ? '♥ Sudah Like (Klik untuk Batal)' : '♡ Like'} (${p.reactions_count ?? 0})
                        </button>
                        <span style="font-size:0.84rem; color:var(--muted);">Komentar: ${p.comments_count ?? 0}</span>
                        ${isMine ? `<button class="danger" style="margin-left:auto;" onclick="deletePost(${p.id})">Hapus Post</button>` : ''}
                    </div>
                    <div style="display:flex; gap:6px; margin-top:10px;">
                        <input type="text" id="commentInput-${p.id}" placeholder="Tulis komentar..." style="margin:0;">
                        <button onclick="sendComment(${p.id})">Kirim</button>
                    </div>
                    </div>`;
                }
                box.innerHTML = html;
            } catch (e) {
                box.innerHTML = `<p style="color:var(--muted);">Gagal memuat feed: ${e.message}</p>`;
            }
        }

        async function searchUsers() {
            const q = document.getElementById('searchQuery').value.trim();
            const box = document.getElementById('searchResults');
            try {
                const res = await api('/users/search?q=' + encodeURIComponent(q));
                const list = Array.isArray(res.data) ? res.data : (res.data?.data || []);
                box.innerHTML = list.map(u => `
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:6px 0; border-bottom:1px solid var(--border);">
                        <span><strong>@${u.username}</strong> (${u.name})</span>
                        <div style="display:flex; gap:4px;">
                            <button style="padding:4px 8px; font-size:0.75rem;" onclick="followUser('${u.username}')">Follow</button>
                            <button class="secondary" style="padding:4px 8px; font-size:0.75rem;" onclick="unfollowUser('${u.username}')">Unfollow</button>
                        </div>
                    </div>
                `).join('') || 'Tidak ada user ditemukan.';
            } catch (e) { showAlert(e.message, true); }
        }

        async function followUser(username) {
            try {
                await api(`/users/${username}/follow`, { method: 'POST' });
                showAlert('Berhasil mengikuti @' + username);
                await loadFeed();
            } catch (e) { showAlert(e.message, true); }
        }

        async function unfollowUser(username) {
            try {
                await api(`/users/${username}/follow`, { method: 'DELETE' });
                showAlert('Berhenti mengikuti @' + username);
                await loadFeed();
            } catch (e) { showAlert(e.message, true); }
        }

        async function loadNotifications() {
            const box = document.getElementById('notifContainer');
            try {
                const res = await api('/notifications');
                const items = Array.isArray(res.data) ? res.data : (res.data?.data || []);
                if (!items.length) {
                    box.innerHTML = 'Belum ada notifikasi.';
                    return;
                }
                box.innerHTML = items.map(n => `
                    <div style="padding:7px 0; border-bottom:1px solid var(--border); color:${n.read_at ? 'var(--muted)' : 'var(--text)'};">
                        <strong>${n.title}</strong><br>${n.body}
                    </div>
                `).join('');
            } catch (e) {
                box.innerHTML = 'Modul notifikasi belum aktif.';
            }
        }

        async function markAllNotificationsRead() {
            try {
                await api('/notifications/read-all', { method: 'PATCH' });
                await loadNotifications();
            } catch (e) {}
        }

        async function loadAll() {
            await loadFeed();
            await loadNotifications();
        }

        async function initApp() {
            if (!token) return;
            try {
                const me = await api('/auth/me');
                currentUser = me.data;
                document.getElementById('authSection').classList.add('hidden');
                document.getElementById('appSection').classList.remove('hidden');
                document.getElementById('navUser').classList.remove('hidden');
                document.getElementById('currentUserLabel').textContent = '@' + currentUser.username + ' (' + currentUser.name + ')';
                await loadAll();
            } catch (e) {
                token = '';
                localStorage.removeItem('instaclone_token');
            }
        }

        initApp();
    </script>
</body>
</html>