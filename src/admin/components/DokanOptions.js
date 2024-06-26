import React, { useMemo } from 'react';
import { Button, Card, CardActions, CardOverflow, FormControl, FormHelperText, FormLabel, Stack, Switch, Divider, Select, Option, Input, Typography, CardContent } from '@mui/joy';
import { ToastContainer } from 'react-toastify';
import "react-toastify/dist/ReactToastify.css";

import useSettings from '../hooks/useSettings';
import { handleSwitchChange, handleInputChange, handleSelectChange } from '../utils/inputHandlers';
import useCheckStatus from '../hooks/useCheckStatus';

const DokanOptions = () => {
    const initialSettings = useMemo(() => ({
        limit_file_upload_size       : '',
        sort_product_by_vendor       : '',
        sold_by_label                : '',
        hide_vendor_dashboard_widgets: {
            big_counter       : false,
            orders            : false,
            products          : false,
            reviews           : false,
            sales_report_chart: false,
            announcement      : false
        },
        hide_product_form_sections   : {
            download_virtual     : false,
            inventory            : false,
            downloadable         : false,
            other_options        : false,
            shipping_tax         : false,
            linked_products      : false,
            attributes           : false,
            discount_options     : false,
            products_seo_yoast   : false,
            products_seo_rankmath: false,
            geolocation          : false,
            rma_options          : false,
            product_addons       : false,
            wholesale            : false,
            order_minmax         : false,
            advertise            : false
        },
        default_product_stock        : '',
        product_individual_sale      : ''
    }), []);

    const { settings, setSettings, updateSettings } = useSettings( 'dokan', initialSettings) ;
    const isDokanProActive = useCheckStatus( 'plugin', 'dokan-pro/dokan-pro.php' );

    const sort_product_by_vendor_options = [
        { value: '', label: 'None' },
        { value: 'asc', label: 'Ascending' },
        { value: 'desc', label: 'Descending' }
    ];
    
    const sold_by_label_options = [
        { value: '', label: 'None' },
        { value: 'product-title', label: 'After Product Title' },
        { value: 'product-price', label: 'Before Add to Cart Button' },
        { value: 'add-to-cart', label: 'After Add to Cart Button' }
    ];

    const onSwitchChange = handleSwitchChange(setSettings);
    const onInputChange = handleInputChange(setSettings);
    const onSelectChange = handleSelectChange(setSettings);

    const handleSubmit = (event) => {
        event.preventDefault();
        updateSettings(settings);
    };
    
    return(
        <form noValidate onSubmit={handleSubmit}>
            <Stack
                direction={'row'}
                justifyContent={'space-between'}
                alignItems={'center'}
                sx={{ my: 1 }}
            >
                <FormControl>
                    <FormLabel>Limit File Upload Size</FormLabel>
                    <FormHelperText>Limit vendor from uploading file size</FormHelperText>
                </FormControl>
                <Input
                    variant="outlined"
                    size='lg'
                    type="number"
                    name='limit_file_upload_size'
                    value={settings.limit_file_upload_size}
                    sx={{ width: 180 }}
                    onChange={onInputChange}
                    endDecorator={
                        <React.Fragment>
                            <Divider orientation="vertical" />
                            <Typography>
                                MB
                            </Typography>
                        </React.Fragment>
                    }
                />
            </Stack>
            <Divider />

            <Stack
                direction={'row'}
                justifyContent={'space-between'}
                alignItems={'center'}
                sx={{ my: 1 }}
            >
                <FormControl>
                    <FormLabel>Sort Product by Vendor</FormLabel>
                    <FormHelperText>Sort products by vendor name on the cart</FormHelperText>
                </FormControl>
                <Select
                    placeholder="Choose one..."
                    name='sort_product_by_vendor'
                    value={settings.sort_product_by_vendor}
                    onChange={onSelectChange('sort_product_by_vendor')}
                >
                    
                    { sort_product_by_vendor_options.map(option => (
                        <Option key={option.value} value={option.value}>
                            {option.label}
                        </Option>
                    )) }
                </Select>
            </Stack>
            <Divider />

            <Stack
                direction={'row'}
                justifyContent={'space-between'}
                alignItems={'center'}
                sx={{ my: 1 }}
            >
                <FormControl>
                    <FormLabel>Sort by Label</FormLabel>
                    <FormHelperText>Display sold by label on the shop page</FormHelperText>
                </FormControl>
                <Select
                    placeholder="Choose one..."
                    name='sold_by_label_options'
                    value={settings.sold_by_label_options}
                    onChange={onSelectChange('sold_by_label_options')}
                >
                    
                    { sold_by_label_options.map(option => (
                        <Option key={option.value} value={option.value}>
                            {option.label}
                        </Option>
                    )) }
                </Select>
            </Stack>
            <Divider />

            <Stack
                direction={'column'}
                sx={{ my: 1 }}
            >
                <FormControl sx={{ pb: 1 }}>
                    <FormLabel>Hide Vendor Dashboard Widgets</FormLabel>
                    <FormHelperText>Hide Vendor Dashboard - Dashboard menu screen widgets</FormHelperText>
                </FormControl>
                <Card>
                    <Stack
                        direction={'column'}
                        spacing={1}
                        sx={{
                            width: "80%",
                            m: "auto"
                        }}
                    >
                        <Typography 
                            level='body-sm'
                            component={'label'}
                            endDecorator={
                                <Switch
                                    size='sm'
                                    name='big_counter_widget'
                                    checked={Boolean(settings.hide_vendor_dashboard_widgets.big_counter)}
                                    onChange={onSwitchChange('hide_vendor_dashboard_widgets', 'big_counter')}
                                />
                            }
                        >
                            Big Counter
                        </Typography>
                        <Typography
                            level='body-sm' 
                            component={'label'}
                            endDecorator={
                                <Switch
                                    size='sm'
                                    name='orders_widget'
                                    checked={Boolean(settings.hide_vendor_dashboard_widgets.orders)}
                                    onChange={onSwitchChange('hide_vendor_dashboard_widgets', 'orders')}
                                />
                            }
                        >
                            Orders
                        </Typography>
                        <Typography
                            level='body-sm' 
                            component={'label'}
                            endDecorator={
                                <Switch
                                    size='sm'
                                    name='products_widget'
                                    checked={Boolean(settings.hide_vendor_dashboard_widgets.products)}
                                    onChange={onSwitchChange('hide_vendor_dashboard_widgets', 'products')}
                                />
                            }
                        >
                            Products
                        </Typography>
                        <Typography
                            level='body-sm' 
                            component={'label'}
                            endDecorator={
                                <Switch
                                    size='sm'
                                    name='sales_report_chart_widget'
                                    checked={Boolean(settings.hide_vendor_dashboard_widgets.sales_report_chart)}
                                    onChange={onSwitchChange('hide_vendor_dashboard_widgets', 'sales_report_chart')}
                                />
                            }
                        >
                            Sales Report Chart
                        </Typography>

                        { isDokanProActive && (  
                            <>
                                <Typography
                                    level='body-sm' 
                                    component={'label'}
                                    endDecorator={
                                        <Switch
                                            size='sm'
                                            name='reviews_widget'
                                            checked={Boolean(settings.hide_vendor_dashboard_widgets.reviews)}
                                            onChange={onSwitchChange('hide_vendor_dashboard_widgets', 'reviews')}
                                        />
                                    }
                                >
                                    Reviews
                                </Typography>                        
                                <Typography
                                    level='body-sm' 
                                    component={'label'}
                                    endDecorator={
                                        <Switch
                                            size='sm'
                                            name='announcement_widget'
                                            checked={Boolean(settings.hide_vendor_dashboard_widgets.announcement)}
                                            onChange={onSwitchChange('hide_vendor_dashboard_widgets', 'announcement')}
                                        />
                                    }
                                >
                                    Announcement
                                </Typography>
                            </>
                        ) }
                    </Stack>
                    </Card>
            </Stack>
            <Divider />

            <Stack
                direction={'column'}
                sx={{ my: 1 }}
            >
                <FormControl sx={{ pb: 1 }}>
                    <FormLabel>Hide Product Form Sections</FormLabel>
                    <FormHelperText>Hide Vendor Dashboard - Product Form sections</FormHelperText>
                </FormControl>

                <Card>
                <Stack
                    direction={'column'}
                    spacing={1}
                    sx={{
                        width: "80%",
                        m: "auto"
                    }}
                >
                    <Typography
                        level='body-sm' 
                        component={'label'}
                        endDecorator={
                            <Switch
                                size='sm'
                                name='download_virtual_section'
                                checked={Boolean(settings.hide_product_form_sections.download_virtual)}
                                onChange={onSwitchChange('hide_product_form_sections', 'download_virtual')}
                            />
                        }
                    >
                        Downloads & Virtual Checkboxes
                    </Typography>

                    <Typography
                        level='body-sm' 
                        component={'label'}
                        endDecorator={
                            <Switch
                                size='sm'
                                name='inventory_section'
                                checked={Boolean(settings.hide_product_form_sections.inventory)}
                                onChange={onSwitchChange('hide_product_form_sections', 'inventory')}
                            />
                        }
                    >
                        Inventory
                    </Typography>
                    
                    <Typography
                        level='body-sm' 
                        component={'label'}
                        endDecorator={
                            <Switch
                                size='sm'
                                name='downloadable'
                                checked={Boolean(settings.hide_product_form_sections.downloadable)}
                                onChange={onSwitchChange('hide_product_form_sections', 'downloadable')}
                            />
                        }
                    >
                        Downloadable
                    </Typography>

                    <Typography
                        level='body-sm' 
                        component={'label'}
                        endDecorator={
                            <Switch
                                size='sm'
                                name='other_options'
                                checked={Boolean(settings.hide_product_form_sections.other_options)}
                                onChange={onSwitchChange('hide_product_form_sections', 'other_options')}
                            />
                        }
                    >
                        Other Options
                    </Typography>

                    { isDokanProActive && (
                        <>
                            <Typography
                                level='body-sm' 
                                component={'label'}
                                endDecorator={
                                    <Switch
                                        size='sm'
                                        name='shipping_tax'
                                        checked={Boolean(settings.hide_product_form_sections.shipping_tax)}
                                        onChange={onSwitchChange('hide_product_form_sections', 'shipping_tax')}
                                    />
                                }
                            >
                                Shipping & Tax
                            </Typography>

                            <Typography
                                level='body-sm' 
                                component={'label'}
                                endDecorator={
                                    <Switch
                                        size='sm'
                                        name='linked_products'
                                        checked={Boolean(settings.hide_product_form_sections.linked_products)}
                                        onChange={onSwitchChange('hide_product_form_sections', 'linked_products')}
                                    />
                                }
                            >
                                Linked Products
                            </Typography>

                            <Typography
                                level='body-sm' 
                                component={'label'}
                                endDecorator={
                                    <Switch
                                        size='sm'
                                        name='attributes'
                                        checked={Boolean(settings.hide_product_form_sections.attributes)}
                                        onChange={onSwitchChange('hide_product_form_sections', 'attributes')}
                                    />
                                }
                            >
                                Attributes & Variations
                            </Typography>

                            <Typography
                                level='body-sm' 
                                component={'label'}
                                endDecorator={
                                    <Switch
                                        size='sm'
                                        name='discount_options'
                                        checked={Boolean(settings.hide_product_form_sections.discount_options)}
                                        onChange={onSwitchChange('hide_product_form_sections', 'discount_options')}
                                    />
                                }
                            >
                                Discount
                            </Typography>

                            <Typography
                                level='body-sm' 
                                component={'label'}
                                endDecorator={
                                    <Switch
                                        size='sm'
                                        name='products_seo_yoast'
                                        checked={Boolean(settings.hide_product_form_sections.products_seo_yoast)}
                                        onChange={onSwitchChange('hide_product_form_sections', 'products_seo_yoast')}
                                    />
                                }
                            >
                                Products SEO (Yoast)
                            </Typography>

                            <Typography
                                level='body-sm' 
                                component={'label'}
                                endDecorator={
                                    <Switch
                                        size='sm'
                                        name='products_seo_rankmath'
                                        checked={Boolean(settings.hide_product_form_sections.products_seo_rankmath)}
                                        onChange={onSwitchChange('hide_product_form_sections', 'products_seo_rankmath')}
                                    />
                                }
                            >
                                Products SEO (RankMath)
                            </Typography>

                            <Typography
                                level='body-sm' 
                                component={'label'}
                                endDecorator={
                                    <Switch
                                        size='sm'
                                        name='geolocation'
                                        checked={Boolean(settings.hide_product_form_sections.geolocation)}
                                        onChange={onSwitchChange('hide_product_form_sections', 'geolocation')}
                                    />
                                }
                            >
                                Geolocation
                            </Typography>

                            <Typography
                                level='body-sm' 
                                component={'label'}
                                endDecorator={
                                    <Switch
                                        size='sm'
                                        name='rma_options'
                                        checked={Boolean(settings.hide_product_form_sections.rma_options)}
                                        onChange={onSwitchChange('hide_product_form_sections', 'rma_options')}
                                    />
                                }
                            >
                                RMA Options
                            </Typography>

                            <Typography
                                level='body-sm' 
                                component={'label'}
                                endDecorator={
                                    <Switch
                                        size='sm'
                                        name='product_addons'
                                        checked={Boolean(settings.hide_product_form_sections.product_addons)}
                                        onChange={onSwitchChange('hide_product_form_sections', 'product_addons')}
                                    />
                                }
                            >
                                Add-ons
                            </Typography>

                            <Typography
                                level='body-sm' 
                                component={'label'}
                                endDecorator={
                                    <Switch
                                        size='sm'
                                        name='wholesale'
                                        checked={Boolean(settings.hide_product_form_sections.wholesale)}
                                        onChange={onSwitchChange('hide_product_form_sections', 'wholesale')}
                                    />
                                }
                            >
                                Wholesale
                            </Typography>

                            <Typography
                                level='body-sm' 
                                component={'label'}
                                endDecorator={
                                    <Switch
                                        size='sm'
                                        name='order_minmax'
                                        checked={Boolean(settings.hide_product_form_sections.order_minmax)}
                                        onChange={onSwitchChange('hide_product_form_sections', 'order_minmax')}
                                    />
                                }
                            >
                                Min/Max Options
                            </Typography>

                            <Typography
                                level='body-sm' 
                                component={'label'}
                                endDecorator={
                                    <Switch
                                        size='sm'
                                        name='advertise'
                                        checked={Boolean(settings.hide_product_form_sections.advertise)}
                                        onChange={onSwitchChange('hide_product_form_sections', 'advertise')}
                                    />
                                }
                            >
                                Advertise Product
                            </Typography>
                        </>
                    ) }
                </Stack>
                </Card>

            </Stack>
            <Divider />

            <Stack
                direction={'row'}
                justifyContent={'space-between'}
                alignItems={'center'}
                sx={{ my: 1 }}
            >
                <FormControl>
                    <FormLabel>Default Product Stock</FormLabel>
                    <FormHelperText>Insert default product stock amount</FormHelperText>
                </FormControl>
                <Input
                    variant="outlined"
                    size='lg'
                    type="number"
                    name='default_product_stock'
                    value={settings.default_product_stock}
                    onChange={onInputChange}
                    sx={{width: 180}}
                />
            </Stack>
            <Divider />
            
            <Stack
                direction={'row'}
                justifyContent={'space-between'}
                alignItems={'center'}
                sx={{ my: 1 }}
            >
                <FormControl>
                    <FormLabel>Product Individual Sale</FormLabel>
                    <FormHelperText>Prevent customers from purchasing one product multiple times at a time</FormHelperText>
                </FormControl>
                <Switch
                    size='lg'
                    name='product_individual_sale'
                    checked={Boolean(settings.product_individual_sale)}
                    onChange={onSwitchChange('product_individual_sale')}
                />
            </Stack>

            <CardOverflow
                sx={{
                    borderTop: '1px solid',
                    borderColor: 'divider',
                    mt: 2
                }}
            >
                <CardActions
                    sx={{
                        alignSelf: 'flex-end',
                        p: '15px 0 0'
                    }}
                >
                    <Button type='submit'>Save Changes</Button>
                    <ToastContainer autoClose={2000} />
                </CardActions>
            </CardOverflow>
        </form>
    );
}

export default DokanOptions;