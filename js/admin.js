let currentTable = null;
let currentColumns = [];
let primaryKey = null;
let currentPage = 1;
let isViewMode = false;
let editingRow = null;

document.addEventListener('DOMContentLoaded', function() {
    loadTablesAndViews();
    setupFormEvents();
});

async function loadTablesAndViews() {
    try {
        const response = await fetch('php/admin_handler.php?action=get_tables');
        const result = await response.json();
        
        if (result.success) {
            renderButtons('tables-list', result.tables, false);
            renderButtons('views-list', result.views, true);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function renderButtons(containerId, items, isView) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';
    
    items.forEach(name => {
        const btn = document.createElement('button');
        btn.className = 'admin-table-btn' + (isView ? ' view-btn' : '');
        btn.textContent = name;
        btn.addEventListener('click', () => selectTable(name, isView));
        container.appendChild(btn);
    });
}

async function selectTable(tableName, isView) {
    currentTable = tableName;
    isViewMode = isView;
    currentPage = 1;
    editingRow = null;
    
    document.querySelectorAll('.admin-table-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.textContent === tableName) btn.classList.add('active');
    });
    
    document.getElementById('data-section').style.display = 'block';
    document.getElementById('form-section').style.display = 'none';
    document.getElementById('current-table-name').textContent = tableName + (isView ? ' (Read Only)' : '');
    
    document.getElementById('add-btn').style.display = isView ? 'none' : 'inline-block';
    
    await loadTableStructure();
    await loadTableData();
}

async function loadTableStructure() {
    try {
        const response = await fetch(`php/admin_handler.php?action=get_table_structure&table=${encodeURIComponent(currentTable)}`);
        const result = await response.json();
        
        if (result.success) {
            currentColumns = result.columns;
            primaryKey = currentColumns.find(col => col.is_primary);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function loadTableData() {
    const thead = document.getElementById('table-head');
    const tbody = document.getElementById('table-body');
    const pagination = document.getElementById('pagination');
    
    thead.innerHTML = '<tr><td colspan="100%" class="empty-state">Loading...</td></tr>';
    tbody.innerHTML = '';
    pagination.innerHTML = '';
    
    try {
        const response = await fetch(`php/admin_handler.php?action=get_table_data&table=${encodeURIComponent(currentTable)}&page=${currentPage}`);
        const result = await response.json();
        
        if (result.success) {
            renderTable(result.data);
            renderPagination(result.page, result.total_pages, result.total);
        }
    } catch (error) {
        console.error('Error:', error);
        tbody.innerHTML = '<tr><td colspan="100%" class="empty-state">Error loading data</td></tr>';
    }
}

function renderTable(data) {
    const thead = document.getElementById('table-head');
    const tbody = document.getElementById('table-body');
    
    let headHtml = '<tr>';
    currentColumns.forEach(col => {
        headHtml += `<th>${col.field}</th>`;
    });
    if (!isViewMode) headHtml += '<th>Actions</th>';
    headHtml += '</tr>';
    thead.innerHTML = headHtml;
    
    if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="${currentColumns.length + (isViewMode ? 0 : 1)}" class="empty-state">No records</td></tr>`;
        return;
    }
    
    let bodyHtml = '';
    data.forEach(row => {
        bodyHtml += '<tr>';
        currentColumns.forEach(col => {
            const value = row[col.field];
            const display = value === null ? '<em style="opacity:0.5">NULL</em>' : escapeHtml(String(value));
            bodyHtml += `<td title="${escapeHtml(String(value || ''))}">${display}</td>`;
        });
        
        if (!isViewMode) {
            const pkValue = row[primaryKey.field];
            bodyHtml += `
                <td class="actions-cell">
                    <button class="action-btn edit" data-row='${JSON.stringify(row).replace(/'/g, "&apos;")}' title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn delete" data-key="${primaryKey.field}" data-value="${pkValue}" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
        }
        bodyHtml += '</tr>';
    });
    tbody.innerHTML = bodyHtml;
    
    if (!isViewMode) {
        tbody.querySelectorAll('.action-btn.edit').forEach(btn => {
            btn.addEventListener('click', () => {
                const row = JSON.parse(btn.dataset.row.replace(/&apos;/g, "'"));
                openEditForm(row);
            });
        });
        
        tbody.querySelectorAll('.action-btn.delete').forEach(btn => {
            btn.addEventListener('click', () => {
                if (confirm('Delete this record?')) {
                    deleteRecord(btn.dataset.key, btn.dataset.value);
                }
            });
        });
    }
}

function renderPagination(page, totalPages, total) {
    const pagination = document.getElementById('pagination');
    
    if (totalPages <= 1) {
        pagination.innerHTML = `<span class="pagination-info">Total: ${total} records</span>`;
        return;
    }
    
    let html = '';
    html += `<button class="pagination-btn" ${page === 1 ? 'disabled' : ''} data-page="${page - 1}">← Prev</button>`;
    
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= page - 1 && i <= page + 1)) {
            html += `<button class="pagination-btn ${i === page ? 'active' : ''}" data-page="${i}">${i}</button>`;
        } else if (i === page - 2 || i === page + 2) {
            html += '<span style="color: var(--color-text-muted);">...</span>';
        }
    }
    
    html += `<button class="pagination-btn" ${page === totalPages ? 'disabled' : ''} data-page="${page + 1}">Next →</button>`;
    html += `<span class="pagination-info">Total: ${total}</span>`;
    
    pagination.innerHTML = html;
    
    pagination.querySelectorAll('.pagination-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (!btn.disabled) {
                currentPage = parseInt(btn.dataset.page);
                loadTableData();
            }
        });
    });
}

document.getElementById('add-btn').addEventListener('click', openAddForm);

function openAddForm() {
    editingRow = null;
    document.getElementById('form-title').textContent = `Add to ${currentTable}`;
    renderFormFields({});
    document.getElementById('form-section').style.display = 'block';
    document.getElementById('form-section').scrollIntoView({ behavior: 'smooth' });
}

function openEditForm(row) {
    editingRow = row;
    document.getElementById('form-title').textContent = `Edit in ${currentTable}`;
    renderFormFields(row);
    document.getElementById('form-section').style.display = 'block';
    document.getElementById('form-section').scrollIntoView({ behavior: 'smooth' });
}

function renderFormFields(data) {
    const container = document.getElementById('form-fields');
    container.innerHTML = '';
    
    currentColumns.forEach(col => {
        if (col.is_auto_increment) return;
        
        const field = document.createElement('div');
        field.className = 'form-field';
        
        const label = document.createElement('label');
        label.textContent = col.field;
        field.appendChild(label);
        
        const value = data[col.field] !== undefined ? data[col.field] : (col.default !== null ? col.default : '');
        let input;
        
        if (col.type.includes('text')) {
            input = document.createElement('textarea');
            input.rows = 3;
        } else if (col.type.includes('int') || col.type.includes('decimal')) {
            input = document.createElement('input');
            input.type = 'number';
            input.step = col.type.includes('decimal') ? '0.1' : '1';
        } else if (col.type.includes('date')) {
            input = document.createElement('input');
            input.type = 'date';
        } else {
            input = document.createElement('input');
            input.type = 'text';
        }
        
        input.name = col.field;
        input.value = value !== null ? value : '';
        if (col.null === 'NO' && col.default === null) input.required = true;
        
        field.appendChild(input);
        
        const hint = document.createElement('div');
        hint.className = 'field-hint';
        hint.textContent = col.type;
        field.appendChild(hint);
        
        container.appendChild(field);
    });
}

function setupFormEvents() {
    document.getElementById('record-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('table_name', currentTable);
        
        const isEdit = editingRow !== null;
        const action = isEdit ? 'update_record' : 'add_record';
        
        if (isEdit) {
            formData.append('primary_key', primaryKey.field);
            formData.append('primary_key_value', editingRow[primaryKey.field]);
            formData.delete(primaryKey.field);
        }
        
        try {
            const response = await fetch(`php/admin_handler.php?action=${action}`, {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert(result.message);
                document.getElementById('form-section').style.display = 'none';
                this.reset();
                await loadTableData();
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error saving record');
        }
    });
    
    document.getElementById('cancel-form-btn').addEventListener('click', () => {
        document.getElementById('form-section').style.display = 'none';
        document.getElementById('record-form').reset();
    });
}

async function deleteRecord(key, value) {
    if (!confirm('Delete this record? This cannot be undone.')) return;
    
    console.log('Deleting:', currentTable, key, value);
    
    const formData = new FormData();
    formData.append('table_name', currentTable);
    formData.append('primary_key', key);
    formData.append('primary_key_value', String(value));
    
    try {
        const response = await fetch('php/admin_handler.php?action=delete_record', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        console.log('Delete result:', result);
        
        if (result.success) {
            alert('Record deleted successfully');
            await loadTableData();
        } else {
            alert('Error: ' + (result.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error deleting record: ' + error.message);
    }
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}