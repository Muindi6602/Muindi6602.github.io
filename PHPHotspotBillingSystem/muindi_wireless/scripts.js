// Add event listener to navigation links
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const page = e.target.getAttribute('data-page');
        loadContent(page);
    });
});

// Function to load the content dynamically
function loadContent(page) {
    const contentDiv = document.getElementById('content');
    contentDiv.innerHTML = ''; // Clear the content first

    switch (page) {
        case 'home':
            window.location.href = 'manchester.php';
            break;
        case 'topup':
            contentDiv.innerHTML = `
                <h3>Top-up</h3>
                <p>Coming soon...</p>
            `;
            break;
        case 'activate':
            contentDiv.innerHTML = `
                <h3>Activate</h3>
                <p>Coming soon...</p>
            `;
            break;
        case 'refill':
            contentDiv.innerHTML = `
                <h3>Refill History</h3>
                <p>Coming soon...</p>
            `;
            break;
        case 'traffic':
            contentDiv.innerHTML = `
                <h3>Traffic History</h3>
                <p>Coming soon...</p>
            `;
            break;
        case 'subscription':
            contentDiv.innerHTML = `
                <h3>Subscription History</h3>
                <p>Coming soon...</p>
            `;
            break;
        case 'live':
            contentDiv.innerHTML = `
                <h3>Chat with Admin</h3>
                <p>Coming soon...</p>
            `;
            break;
            case 'credentials':
                contentDiv.innerHTML = `
                    <table>
                        <tr>
                            <td>STK</td>
                            <td>Soon...</td>
                        </tr>
                        <tr>
                            <td>Change password</td>
                            <td>Reset</td>
                        </tr>
                        <tr>
                            <td>Disconnect</td>
                            <td>N/A</td>
                        </tr>
                        <tr>
                            <td>Current Package</td>
                            <td>Wireless 25 Mbps @ Kshs 2500</td>
                        </tr>
                    </table>
                `;
                break;

            default:
                contentDiv.innerHTML = `<p>Page not found.</p>`;
        }
    }