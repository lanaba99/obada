// resources/js/app.jsx

// This line imports Laravel's default JavaScript bootstrap file.
// It often sets up Axios (for making HTTP requests) if it's installed.
// We might adjust it later, but it's good to keep for now.
import './bootstrap';

import React from 'react';
import { createRoot } from 'react-dom/client';

// This is your first React component!
function HelloWorld() {
    return (
        <h1>Hello React from Royal Crown! 👋</h1>
    );
}

// This is where React will render your application.
// It looks for an HTML element with the id "app".
const container = document.getElementById('app');

if (container) {
    // Create a "root" for your React app.
    const root = createRoot(container);
    // Render your HelloWorld component into this root.
    root.render(
        <React.StrictMode> {/* Helps catch potential problems in your app */}
            <HelloWorld />
        </React.StrictMode>
    );
} else {
    console.error("Fatal Error: The HTML element with id 'app' was not found. React cannot mount.");
}