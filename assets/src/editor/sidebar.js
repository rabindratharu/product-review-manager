/**
 * Meta Options build.
 */
import { PluginSidebar } from "@wordpress/edit-post";
import { __ } from "@wordpress/i18n";
import { Component, Fragment } from "@wordpress/element";
import { PanelBody, TextControl, SelectControl } from "@wordpress/components";
import { withSelect, withDispatch } from "@wordpress/data";
import { compose } from "@wordpress/compose";

class PrmSidebar extends Component {
    constructor() {
        super(...arguments);
        this.state = {};
    }

    render() {
        const { meta, setMetaFieldValue, products } = this.props;

        // Prepare product options for SelectControl
        const productOptions = [
            { label: __('Select a Product', 'product-review-manager'), value: '' },
            ...products.map((product) => ({
                label: product.title.rendered,
                value: product.id,
            })),
        ];

        return (
            <Fragment>
                <PluginSidebar name="prm-sidebar" title={__('Product Review Manager', 'product-review-manager')}>
                    <PanelBody title={__('Review Details', 'product-review-manager')}>
                        <SelectControl
                            label={__('Product Name', 'product-review-manager')}
                            value={meta.prm_product_name || ''}
                            options={productOptions}
                            onChange={(value) => setMetaFieldValue(value, 'prm_product_name')}
                        />
                        <SelectControl
                            label={__('Rating (1-5)', 'product-review-manager')}
                            value={meta.prm_rating || ''}
                            options={[
                                { label: __('Select Rating', 'product-review-manager'), value: '' },
                                { label: '1', value: '1' },
                                { label: '2', value: '2' },
                                { label: '3', value: '3' },
                                { label: '4', value: '4' },
                                { label: '5', value: '5' },
                            ]}
                            onChange={(value) => setMetaFieldValue(value, 'prm_rating')}
                        />
                        <TextControl
                            label={__("Reviewer's Name", "product-review-manager")}
                            value={meta.prm_reviewer_name || ''}
                            onChange={(value) => setMetaFieldValue(value, 'prm_reviewer_name')}
                        />
                    </PanelBody>
                </PluginSidebar>
            </Fragment>
        );
    }
}

export default compose(
    withSelect((select) => {
        // Retrieve the current post's saved meta
        const meta = select("core/editor").getCurrentPostAttribute("meta") || {};
        // Fetch all WooCommerce products
        const products = select('core').getEntityRecords('postType', 'product', {
            per_page: -1, // Retrieve all products
            status: 'publish', // Only published products
        }) || [];

        return {
            meta,
            products,
        };
    }),
    withDispatch((dispatch) => ({
        setMetaFieldValue: (value, field) =>
            dispatch("core/editor").editPost({ meta: { [field]: value } }),
    }))
)(PrmSidebar);