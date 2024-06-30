/**
 * Settings component for rendering admin settings page.
 *
 * This component manages the selected option state using localStorage.
 * It renders a sidebar and content based on the selected option.
 *
 * @since 2.0.0
 */

import React, { useState, useEffect } from "react";
import { Box } from "@mui/joy";

import Sidebar from "./components/Sidebar";
import Content from "./components/Content";

const Settings = () => {
    const [selectedOption, setSelectedOption] = useState('WooCommerce');
    
    // Read the selected option from localStorage on component mount
    useEffect(() => {
        const savedOption = localStorage.getItem('selectedOption');
        if (savedOption) {
            setSelectedOption(savedOption);
        }
    }, []);

    // Save the selected option to localStorage whenever it changes
    useEffect(() => {
        localStorage.setItem('selectedOption', selectedOption);
    }, [selectedOption]);

    return (
        <Box 
            sx={{ 
                display: 'flex',
                width: "70%",
                m: "50px auto"
            }}
        >
            {/* Render the sidebar with selected option state */}
            <Sidebar selectedOption={selectedOption} setSelectedOption={setSelectedOption} />
            
            {/* Render the content based on the selected option */}
            <Content selectedOption={selectedOption} />
        </Box> 
    );
}

export default Settings;
