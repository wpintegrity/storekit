import React, { useMemo } from 'react';
import {
    Button,
    Card,
    CardActions,
    CardOverflow,
    FormControl,
    FormHelperText,
    FormLabel,
    Stack,
    Switch,
    Divider,
    Select,
    Option,
    Input,
    Typography
} from '@mui/joy';
import { ToastContainer } from 'react-toastify';
import "react-toastify/dist/ReactToastify.css";

import useSettings from '../hooks/useSettings';
import { handleSwitchChange, handleInputChange, handleSelectChange } from '../utils/inputHandlers';
import useCheckStatus from '../hooks/useCheckStatus';
import { __ } from '@wordpress/i18n'; // Importing the translation function

/**
 * DokanOptions component
 *
 * Renders the Dokan options form and handles the form submission.
 *
 * @since 2.0.0
 *
 * @return {JSX.Element} The rendered component.
 */
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
        product_individual_sale      : 'no'
    }), []);

    const { settings, setSettings, updateSettings } = useSettings('dokan', initialSettings);
    const isDokanProActive = useCheckStatus('plugin', 'dokan-pro/dokan-pro.php');

    const sort_product_by_vendor_options = [
        { value: '', label: __('None', 'storekit') },
        { value: 'asc', label: __('Ascending', 'storekit') },
        { value: 'desc', label: __('Descending', 'storekit') }
    ];
    
    const sold_by_label_options = [
        { value: '', label: __('None', 'storekit') },
        { value: 'product-title', label: __('After Product Title', 'storekit') },
        { value: 'product-price', label: __('Before Add to Cart Button', 'storekit') },
        { value: 'add-to-cart', label: __('After Add to Cart Button', 'storekit') }
    ];

    const onSwitchChange = handleSwitchChange(setSettings);
    const onInputChange = handleInputChange(setSettings);
    const onSelectChange = handleSelectChange(setSettings);

    /**
     * Handle form submission
     *
     * @param {Event} event - The form submit event.
     */
    const handleSubmit = (event) => {
        event.preventDefault();
        updateSettings(settings);
    };
    
    return (
        <form noValidate onSubmit={handleSubmit}>
            <Stack
                direction={'row'}
                justifyContent={'space-between'}
                alignItems={'center'}
                sx={{ my: 1 }}
            >
                <FormControl>
                    <FormLabel>{ __('Limit File Upload Size', 'storekit') }</FormLabel>
                    <FormHelperText>{ __('Limit vendor from uploading file size', 'storekit') }</FormHelperText>
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
                        <>
                            <Divider orientation="vertical" />
                            <Typography>MB</Typography>
                        </>
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
                    <FormLabel>{ __('Sort Product by Vendor', 'storekit') }</FormLabel>
                    <FormHelperText>{ __('Sort products by vendor name on the cart', 'storekit') }</FormHelperText>
                </FormControl>
                <Select
                    placeholder={ __('Choose one...', 'storekit') }
                    name='sort_product_by_vendor'
                    value={settings.sort_product_by_vendor}
                    onChange={onSelectChange('sort_product_by_vendor')}
                >
                    {sort_product_by_vendor_options.map(option => (
                        <Option key={option.value} value={option.value}>
                            {option.label}
                        </Option>
                    ))}
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
                    <FormLabel>{ __('Sort by Label', 'storekit') }</FormLabel>
                    <FormHelperText>{ __('Display sold by label on the shop page', 'storekit') }</FormHelperText>
                </FormControl>
                <Select
                    placeholder={ __('Choose one...', 'storekit') }
                    name='sold_by_label'
                    value={settings.sold_by_label}
                    onChange={onSelectChange('sold_by_label')}
                >
                    {sold_by_label_options.map(option => (
                        <Option key={option.value} value={option.value}>
                            {option.label}
                        </Option>
                    ))}
                </Select>
            </Stack>
            <Divider />

            <Stack
                direction={'column'}
                sx={{ my: 1 }}
            >
                <FormControl sx={{ pb: 1 }}>
                    <FormLabel>{ __('Hide Vendor Dashboard Widgets', 'storekit') }</FormLabel>
                    <FormHelperText>{ __('Hide Vendor Dashboard - Dashboard menu screen widgets', 'storekit') }</FormHelperText>
                </FormControl>
                <Card>
                    <Stack
                        direction={'column'}
                        spacing={1}
                        sx={{ width: "80%", m: "auto" }}
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
                            { __('Big Counter', 'storekit') }
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
                            { __('Orders', 'storekit') }
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
                            { __('Products', 'storekit') }
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
                            { __('Sales Report Chart', 'storekit') }
                        </Typography>

                        {isDokanProActive && (  
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
                                    { __('Reviews', 'storekit') }
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
                                    { __('Announcement', 'storekit') }
                                </Typography>
                            </>
                        )}
                    </Stack>
                </Card>
            </Stack>
            <Divider />

            <Stack
                direction={'column'}
                sx={{ my: 1 }}
            >
                <FormControl sx={{ pb: 1 }}>
                    <FormLabel>{ __('Hide Product Form Sections', 'storekit') }</FormLabel>
                    <FormHelperText>{ __('Hide Vendor Dashboard - Product Form sections', 'storekit') }</FormHelperText>
                </FormControl>

                <Card>
                    <Stack
                        direction={'column'}
                        spacing={1}
                        sx={{ width: "80%", m: "auto" }}
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
                            { __('Downloadable & Virtual Checkbox', 'storekit') }
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
                            { __('Inventory', 'storekit') }
                        </Typography>
                        <Typography 
                            level='body-sm'
                            component={'label'}
                            endDecorator={
                                <Switch
                                    size='sm'
                                    name='downloadable_section'
                                    checked={Boolean(settings.hide_product_form_sections.downloadable)}
                                    onChange={onSwitchChange('hide_product_form_sections', 'downloadable')}
                                />
                            }
                        >
                            { __('Downloadable', 'storekit') }
                        </Typography>

                        <Typography 
                            level='body-sm'
                            component={'label'}
                            endDecorator={
                                <Switch
                                    size='sm'
                                    name='shipping_tax_section'
                                    checked={Boolean(settings.hide_product_form_sections.shipping_tax)}
                                    onChange={onSwitchChange('hide_product_form_sections', 'shipping_tax')}
                                />
                            }
                        >
                            { __('Shipping & Tax', 'storekit') }
                        </Typography>

                        <Typography 
                            level='body-sm'
                            component={'label'}
                            endDecorator={
                                <Switch
                                    size='sm'
                                    name='linked_products_section'
                                    checked={Boolean(settings.hide_product_form_sections.linked_products)}
                                    onChange={onSwitchChange('hide_product_form_sections', 'linked_products')}
                                />
                            }
                        >
                            { __('Linked Products', 'storekit') }
                        </Typography>
                        <Typography 
                            level='body-sm'
                            component={'label'}
                            endDecorator={
                                <Switch
                                    size='sm'
                                    name='attributes_section'
                                    checked={Boolean(settings.hide_product_form_sections.attributes)}
                                    onChange={onSwitchChange('hide_product_form_sections', 'attributes')}
                                />
                            }
                        >
                            { __('Attributes', 'storekit') }
                        </Typography>

                        {isDokanProActive && (  
                            <>
                                <Typography 
                                    level='body-sm'
                                    component={'label'}
                                    endDecorator={
                                        <Switch
                                            size='sm'
                                            name='discount_options_section'
                                            checked={Boolean(settings.hide_product_form_sections.discount_options)}
                                            onChange={onSwitchChange('hide_product_form_sections', 'discount_options')}
                                        />
                                    }
                                >
                                    { __('Discount Options', 'storekit') }
                                </Typography>
                                <Typography 
                                    level='body-sm'
                                    component={'label'}
                                    endDecorator={
                                        <Switch
                                            size='sm'
                                            name='products_seo_yoast_section'
                                            checked={Boolean(settings.hide_product_form_sections.products_seo_yoast)}
                                            onChange={onSwitchChange('hide_product_form_sections', 'products_seo_yoast')}
                                        />
                                    }
                                >
                                    { __('Products SEO (Yoast)', 'storekit') }
                                </Typography>

                                <Typography 
                                    level='body-sm'
                                    component={'label'}
                                    endDecorator={
                                        <Switch
                                            size='sm'
                                            name='products_seo_rankmath_section'
                                            checked={Boolean(settings.hide_product_form_sections.products_seo_rankmath)}
                                            onChange={onSwitchChange('hide_product_form_sections', 'products_seo_rankmath')}
                                        />
                                    }
                                >
                                    { __('Products SEO (RankMath)', 'storekit') }
                                </Typography>

                                <Typography 
                                    level='body-sm'
                                    component={'label'}
                                    endDecorator={
                                        <Switch
                                            size='sm'
                                            name='geolocation_section'
                                            checked={Boolean(settings.hide_product_form_sections.geolocation)}
                                            onChange={onSwitchChange('hide_product_form_sections', 'geolocation')}
                                        />
                                    }
                                >
                                    { __('Geolocation', 'storekit') }
                                </Typography>

                                <Typography 
                                    level='body-sm'
                                    component={'label'}
                                    endDecorator={
                                        <Switch
                                            size='sm'
                                            name='rma_options_section'
                                            checked={Boolean(settings.hide_product_form_sections.rma_options)}
                                            onChange={onSwitchChange('hide_product_form_sections', 'rma_options')}
                                        />
                                    }
                                >
                                    { __('RMA Options', 'storekit') }
                                </Typography>

                                <Typography 
                                    level='body-sm'
                                    component={'label'}
                                    endDecorator={
                                        <Switch
                                            size='sm'
                                            name='product_addons_section'
                                            checked={Boolean(settings.hide_product_form_sections.product_addons)}
                                            onChange={onSwitchChange('hide_product_form_sections', 'product_addons')}
                                        />
                                    }
                                >
                                    { __('Product Addons', 'storekit') }
                                </Typography>

                                <Typography 
                                    level='body-sm'
                                    component={'label'}
                                    endDecorator={
                                        <Switch
                                            size='sm'
                                            name='wholesale_section'
                                            checked={Boolean(settings.hide_product_form_sections.wholesale)}
                                            onChange={onSwitchChange('hide_product_form_sections', 'wholesale')}
                                        />
                                    }
                                >
                                    { __('Wholesale', 'storekit') }
                                </Typography>

                                <Typography 
                                    level='body-sm'
                                    component={'label'}
                                    endDecorator={
                                        <Switch
                                            size='sm'
                                            name='order_minmax_section'
                                            checked={Boolean(settings.hide_product_form_sections.order_minmax)}
                                            onChange={onSwitchChange('hide_product_form_sections', 'order_minmax')}
                                        />
                                    }
                                >
                                    { __('Order Min/Max', 'storekit') }
                                </Typography>

                                <Typography 
                                    level='body-sm'
                                    component={'label'}
                                    endDecorator={
                                        <Switch
                                            size='sm'
                                            name='advertise_section'
                                            checked={Boolean(settings.hide_product_form_sections.advertise)}
                                            onChange={onSwitchChange('hide_product_form_sections', 'advertise')}
                                        />
                                    }
                                >
                                    { __('Advertise', 'storekit') }
                                </Typography>
                        </>
                    )}

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
                    <FormLabel>{ __('Default Product Stock', 'storekit') }</FormLabel>
                    <FormHelperText>{ __('Set default product stock quantity to vendor\'s new product', 'storekit') }</FormHelperText>
                </FormControl>
                <Input
                    variant="outlined"
                    size='lg'
                    type="number"
                    name='default_product_stock'
                    value={settings.default_product_stock}
                    sx={{ width: 180 }}
                    onChange={onInputChange}
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
                    <FormLabel>{ __('Product Individual Sale', 'storekit') }</FormLabel>
                    <FormHelperText>{ __('Allow only one item to be bought in a single order', 'storekit') }</FormHelperText>
                </FormControl>
                <Select
                    placeholder={ __('Choose one...', 'storekit') }
                    name='product_individual_sale'
                    value={settings.product_individual_sale}
                    onChange={onSelectChange('product_individual_sale')}
                >
                    <Option value="no">{ __('No', 'storekit') }</Option>
                    <Option value="yes">{ __('Yes', 'storekit') }</Option>
                </Select>
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
                    <Button type="submit">
                        { __('Save Changes', 'storekit') }
                    </Button>
                </CardActions>
            </CardOverflow>

            <ToastContainer autoClose={2000} />
        </form>
    );
};

export default DokanOptions;
