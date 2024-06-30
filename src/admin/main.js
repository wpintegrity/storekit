/**
 * Entry point for rendering the admin settings interface.
 *
 * This script initializes the React application for the admin settings page.
 *
 * @since 2.0.0
 */

import React from "react";
import ReactDOM from "react-dom/client"; // Using ReactDOM.createRoot for server rendering
import { __ } from '@wordpress/i18n'; // WordPress internationalization functions

import Settings from "./Settings"; // Importing the main Settings component

import '../styles/admin/admin.scss'; // Importing admin styles

// Get the container element where the admin settings will be rendered
const adminSettingsContainer = document.getElementById( 'storekit-admin' );

// Create a root for ReactDOM to render the React app into
const storekitAdmin = ReactDOM.createRoot( adminSettingsContainer );

// Render the main Settings component into the designated container
storekitAdmin.render( <Settings /> );
