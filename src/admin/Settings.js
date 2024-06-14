import React, { useState, useEffect } from "react";
import { Box } from "@mui/joy";

import Sidebar from "./components/Sidebar";
import Content from "./components/Content";

const Settings = () => {
    const [ selectedOption, setSelectedOption ] = useState('WooCommerce');
    
    // Read the selected option from localStorage on mount
    useEffect( () => {
        const savedOption = localStorage.getItem('selectedOption')
        if (savedOption) {
            setSelectedOption(savedOption);
        }
    }, [] )

    // Save the selected option to localStorage whenever it changes
    useEffect( () => {
        localStorage.setItem('selectedOption', selectedOption)
    }, [selectedOption] )

    return (
        <Box 
            sx={{ 
                display: 'flex',
                width: "70%",
                m: "50px auto"
            }}
        >
            <Sidebar selectedOption={selectedOption} setSelectedOption={setSelectedOption} />
            <Content selectedOption={selectedOption} />
        </Box> 
    );
}

export default Settings;