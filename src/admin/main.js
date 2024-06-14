import React from "react";
import ReactDOM from "react-dom/client";
import { __ } from '@wordpress/i18n';

import Settings from "./Settings";

import '../styles/admin/admin.scss';

const adminSettingsContainer = document.getElementById( 'storekit-admin' );
const storekitAdmin = ReactDOM.createRoot( adminSettingsContainer );
storekitAdmin.render( <Settings /> );