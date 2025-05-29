/**
 * Meta Options build.
 */
import { PluginSidebar } from "@wordpress/edit-post";
import { __ } from "@wordpress/i18n";
import { Component } from "@wordpress/element";
import { PanelBody, TextControl } from "@wordpress/components";
import { withSelect, withDispatch } from "@wordpress/data";
import { compose } from "@wordpress/compose";

class PrmSidebar extends Component {
    constructor() {
        super(...arguments);
        this.state = {};
    }
    render() {
        return (
            <>
                <PluginSidebar name="prm-sidebar" title={__('Product Review Manager', 'product-review-manager')}>
                    <PanelBody title="Review Details">
                        <TextControl
                            label={__("Reviewer\'s Name", "product-review-manager")}
                            value={this.props.meta.prm_reviewer_name || ""}
                            onChange={(value) => {
                                this.props.setMetaFieldValue(
                                    value,
                                    "prm_reviewer_name"
                                );
                            }}
                        />
                    </PanelBody>
                </PluginSidebar>
            </>
        );
    }
}
export default compose(
    withSelect((select) => {
        const postMeta = select("core/editor").getEditedPostAttribute("meta");
        const oldPostMeta = select("core/editor").getCurrentPostAttribute("meta");
        return {
            meta: { ...oldPostMeta, ...postMeta },
            oldMeta: oldPostMeta,
        };
    }),
    withDispatch((dispatch) => ({
        setMetaFieldValue: (value, field) =>
            dispatch("core/editor").editPost({ meta: { [field]: value } }),
    }))
)(PrmSidebar);
