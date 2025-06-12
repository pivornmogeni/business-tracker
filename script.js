// Initialize when the document is loaded
document.addEventListener('DOMContentLoaded', function() {
    loadTransactions();
});

// Function to fetch and display transactions
function loadTransactions() {
    fetch('./api/transactions.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#transactionTable tbody');
            tableBody.innerHTML = '';

            if (data.length > 0) {
                data.forEach(transaction => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${new Date(transaction.date).toLocaleDateString()}</td>
                        <td>${transaction.description}</td>
                        <td class="${transaction.type === 'income' ? 'income' : 'expense'}">
                            ${transaction.type.charAt(0).toUpperCase() + transaction.type.slice(1)}
                        </td>
                        <td class="${transaction.type === 'income' ? 'income' : 'expense'}">
                            $${transaction.amount.toFixed(2)}
                        </td>
                        <td>
                            <button class="delete-btn" onclick="deleteTransaction(${transaction.id})">
                                Delete
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            } else {
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="5">No transactions found.</td>';
                tableBody.appendChild(row);
            }
        })
        .catch(error => {
            console.error('Error loading transactions:', error);
            alert('Error loading transactions. Please try again.');
        });
}

// Function to delete a transaction
function deleteTransaction(id) {
    if (confirm('Are you sure you want to delete this transaction?')) {
        fetch(`api/delete_transaction.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.message === 'Transaction deleted successfully') {
                    loadTransactions();
                } else {
                    alert('Error deleting transaction.');
                }
            })
            .catch(error => {
                console.error('Error deleting transaction:', error);
                alert('Error deleting transaction. Please try again.');
            });
    }
}

// Handle form submission for adding transactions
document.getElementById('transactionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form values directly
    const description = this.querySelector('input[type="text"]').value;
    const amount = this.querySelector('input[type="number"]').value;
    const date = this.querySelector('input[type="date"]').value;
    const type = document.querySelector('.transaction-type .active').classList.contains('income') ? 'income' : 'expense';

    // Use URLSearchParams to send form data
    const params = new URLSearchParams({
        description: description,
        amount: amount,
        type: type,
        date: date || new Date().toISOString() // Use provided date or current date
    });

    // Send POST request to PHP script
    fetch('api/add_transaction.php', {
        method: 'POST',
        body: params
    })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Transaction added successfully') {
                alert('Transaction added successfully!');
                this.reset();
                loadTransactions();
            } else {
                alert('Error adding transaction. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error adding transaction:', error);
            alert('Error adding transaction. Please try again.');
        });
});