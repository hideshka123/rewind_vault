let topicsData = [];
let currentCategory = 'all';
let topicsList;
let sidebarLinks;

document.addEventListener('DOMContentLoaded', function() {
    topicsList = document.getElementById('topics-list');
    sidebarLinks = document.querySelectorAll('.sidebar-link');
    
    if (topicsList) {
        loadTopics();
        setupSidebar();
    }
    
    if (document.querySelector('.forum-topic-page')) {
        loadTopic();
    }
});

async function loadTopics(category = 'Film Discussions') {
    if (!topicsList) return;
    
    topicsList.innerHTML = '<p style="text-align: center; color: var(--color-text-muted); padding: 40px;">Loading topics...</p>';
    
    try {
        const response = await fetch(`php/forum_api.php?action=get_topics&category=${encodeURIComponent(category)}`);
        const result = await response.json();
        
        if (result.success && result.topics.length > 0) {
            topicsData = result.topics;
            renderTopics();
        } else {
            topicsList.innerHTML = '<p style="text-align: center; color: var(--color-text-muted); padding: 40px;">No topics found</p>';
        }
    } catch (error) {
        console.error('Error loading topics:', error);
        topicsList.innerHTML = '<p style="text-align: center; color: #ff4444;">Error loading topics</p>';
    }
}

async function loadTopic() {
    const urlParams = new URLSearchParams(window.location.search);
    const topicId = urlParams.get('id');
    
    if (!topicId) {
        showError('No topic ID');
        return;
    }
    
    try {
        const response = await fetch(`php/forum_api.php?action=get_topic&id=${topicId}`);
        const result = await response.json();
        
        if (result.success) {
            displayTopic(result.topic, result.posts);
        } else {
            showError('Topic not found');
        }
    } catch (error) {
        console.error('Error loading topic:', error);
        showError('Error loading topic');
    }
}

function displayTopic(topic, posts) {
    const titleEl = document.getElementById('topic-title');
    const categoryEl = document.getElementById('topic-category');
    const authorEl = document.getElementById('topic-author');
    const dateEl = document.getElementById('topic-date');
    const messageEl = document.getElementById('topic-message');
    const avatarEl = document.getElementById('topic-avatar');
    
    if (titleEl) {
        if (topic.movie_title) {
            titleEl.innerHTML = `${escapeHtml(topic.title)} <span style="color: var(--color-primary); font-size: 0.6em;">(${escapeHtml(topic.movie_title)})</span>`;
        } else {
            titleEl.textContent = topic.title;
        }
    }
    
    if (categoryEl) categoryEl.textContent = topic.category || '';
    if (authorEl) authorEl.textContent = topic.author || '';
    if (dateEl) dateEl.textContent = topic.date || '';
    
    if (avatarEl && topic.author_avatar) {
        avatarEl.src = topic.author_avatar;
        avatarEl.onerror = function() {
            this.src = 'assets/images/avatars/default.png';
        };
    }
    
    // Добавляем постер фильма
    if (topic.movie_poster && topic.id_movie) {
        const movieInfoDiv = document.createElement('div');
        movieInfoDiv.style.cssText = 'display: flex; gap: 20px; margin-bottom: 30px; padding: 20px; background: rgba(255,255,255,0.05); border-radius: 8px; align-items: center;';
        movieInfoDiv.innerHTML = `
            <img src="${topic.movie_poster}" alt="${escapeHtml(topic.movie_title)}" 
                 style="width: 120px; height: 180px; object-fit: cover; border-radius: 4px; box-shadow: 0 4px 12px rgba(0,0,0,0.5);"
                 onerror="this.src='assets/images/placeholders/movie1.png'">
            <div>
                <h3 style="margin: 0 0 10px 0; color: var(--color-primary);">${escapeHtml(topic.movie_title)}</h3>
                <p style="color: var(--color-text-muted); font-size: 14px;">Discussion about this film</p>
            </div>
        `;
        
        if (messageEl) {
            messageEl.parentElement.insertBefore(movieInfoDiv, messageEl);
        }
    }
    
    if (messageEl && posts && posts.length > 0) {
        messageEl.textContent = posts[0].message || '';
        displayReplies(posts.slice(1));
    } else if (messageEl) {
        messageEl.textContent = 'No content';
        displayReplies([]);
    }
}

function displayReplies(posts) {
    const repliesList = document.getElementById('replies-list');
    if (!repliesList) return;
    
    repliesList.innerHTML = '';
    
    if (!posts || posts.length === 0) {
        repliesList.innerHTML = '<p style="color: var(--color-text-muted); padding: 20px;">No replies yet</p>';
        return;
    }
    
    posts.forEach(post => {
        const replyDiv = document.createElement('div');
        replyDiv.className = 'reply-card';
        replyDiv.style.cssText = 'background: rgba(255,255,255,0.05); border-radius: 8px; padding: 20px; margin-bottom: 16px;';
        replyDiv.innerHTML = `
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                <img src="${post.author_avatar}" alt="${escapeHtml(post.author)}" 
                     style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;"
                     onerror="this.src='assets/images/avatars/default.png'">
                <span style="font-weight: 600; color: var(--color-text);">${escapeHtml(post.author)}</span>
                <span style="color: var(--color-text-muted); font-size: 14px;">${post.date}</span>
            </div>
            <p style="color: var(--color-text); line-height: 1.6; margin: 0;">${escapeHtml(post.message)}</p>
        `;
        repliesList.appendChild(replyDiv);
    });
}

function renderTopics() {
    if (!topicsList) return;
    
    topicsList.innerHTML = '';
    
    if (topicsData.length === 0) {
        topicsList.innerHTML = '<p style="text-align: center; color: var(--color-text-muted); padding: 40px;">No topics found</p>';
        return;
    }
    
    topicsData.forEach(topic => {
        const row = document.createElement('div');
        row.className = 'topic-row';
        row.style.cssText = 'display: grid; grid-template-columns: 2fr 1fr 1fr 100px; gap: 16px; padding: 16px; border-bottom: 1px solid rgba(255,255,255,0.1); cursor: pointer; transition: background 0.3s; align-items: center;';
        row.onmouseover = function() { this.style.background = 'rgba(255,255,255,0.05)'; };
        row.onmouseout = function() { this.style.background = 'transparent'; };
        
        let posterHtml = '';
        if (topic.movie_poster && topic.id_movie) {
            posterHtml = `
                <div style="display: flex; align-items: center; gap: 12px;">
                    <img src="${topic.movie_poster}" alt="${escapeHtml(topic.movie_title)}" 
                         style="width: 50px; height: 75px; object-fit: cover; border-radius: 4px;"
                         onerror="this.src='assets/images/placeholders/movie1.png'">
                    <div>
                        ${topic.movie_title ? `<div style="color: var(--color-primary); font-size: 12px; margin-bottom: 4px;">${escapeHtml(topic.movie_title)}</div>` : ''}
                        <div style="font-weight: 600; color: var(--color-text);">${escapeHtml(topic.title)}</div>
                    </div>
                </div>
            `;
        } else {
            posterHtml = `
                <div style="font-weight: 600; color: var(--color-text);">${escapeHtml(topic.title)}</div>
            `;
        }
        
        row.innerHTML = `
            <div>${posterHtml}</div>
            <div>
                <span style="padding: 4px 12px; background: rgba(194, 5, 1, 0.2); color: var(--color-primary); border-radius: 4px; font-size: 12px;">
                    ${escapeHtml(topic.category)}
                </span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <img src="${topic.author_avatar}" alt="${escapeHtml(topic.author)}" 
                     style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;"
                     onerror="this.src='assets/images/avatars/default.png'">
                <span style="color: var(--color-text);">${escapeHtml(topic.author)}</span>
            </div>
            <div style="display: flex; align-items: center; gap: 6px; color: var(--color-text-muted);">
                <i class="far fa-comment-alt"></i>
                <span>${topic.replies || 0}</span>
            </div>
        `;
        
        row.addEventListener('click', () => {
            if (topic.id_topic) {
                window.location.href = `forum-topic.php?id=${topic.id_topic}`;
            }
        });
        
        topicsList.appendChild(row);
    });
}

function setupSidebar() {
    if (!sidebarLinks) return;
    
    sidebarLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const category = link.dataset.category;
            
            sidebarLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');
            
            const categoryName = category === 'all' ? 'Film Discussions' : 'User Topics';
            const forumTitle = document.querySelector('.forum-title');
            if (forumTitle) {
                forumTitle.textContent = categoryName.toUpperCase();
            }
            
            loadTopics(categoryName);
        });
    });
}

function showError(message) {
    const titleEl = document.getElementById('topic-title');
    const messageEl = document.getElementById('topic-message');
    
    if (titleEl) titleEl.textContent = 'Error';
    if (messageEl) messageEl.textContent = message;
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}