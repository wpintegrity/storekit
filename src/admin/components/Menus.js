import React, { useState } from 'react';
import { List, ListItem, ListItemButton, ListItemContent } from '@mui/joy';

const Menus = ({ selectedOption, setSelectedOption }) => {

    return (
        <List
            size='lg'
            sx={{
                gap: 1
            }}
        >
            <ListItem>
                <ListItemButton
                    selected={ selectedOption === 'WooCommerce' }
                    onClick={ () => setSelectedOption( 'WooCommerce' ) }
                >
                    <ListItemContent>WooCommerce</ListItemContent>
                </ListItemButton>
            </ListItem>
            <ListItem>
                <ListItemButton
                    selected={ selectedOption === 'Dokan' }
                    onClick={ () => setSelectedOption( 'Dokan' ) }
                >
                    <ListItemContent>Dokan</ListItemContent>
                </ListItemButton>
            </ListItem>
        </List>
    );
}

export default Menus;