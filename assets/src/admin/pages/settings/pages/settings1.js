/* WordPress */
import { __ } from '@wordpress/i18n';
import { useContext } from '@wordpress/element';

/* Library */
import classNames from 'classnames';
import { cloneDeep } from 'lodash';

/*Atrc*/
import {
    AtrcText,
    AtrcControlText,
    AtrcWireFrameContentSidebar,
    AtrcWireFrameHeaderContentFooter,
    AtrcPrefix,
    AtrcPanelBody,
    AtrcPanelRow,
    AtrcContent,
    AtrcTitleTemplate1
} from 'atrc';

/* Inbuilt */
import { AtrcReduxContextData } from '../../../routes';
import { DocsTitle } from '../../../components/molecules';

/*Local*/
const MainContent = () => {
    const data = useContext(AtrcReduxContextData);

    const { dbSettings, dbUpdateSetting } = data;

    const { setting1 = '', setting2 = '' } = dbSettings;

    return (
        <AtrcContent>
            <AtrcPanelRow>
                <AtrcControlText
                    label={__('Setting 1', 'product-review-manager')}
                    placeholder={__('Enter Text', 'product-review-manager')}
                    value={setting1}
                    onChange={newVal =>
                        dbUpdateSetting('setting1', newVal)
                    }

                />
            </AtrcPanelRow>
            <AtrcPanelRow>
                <AtrcControlText
                    label={__('Setting 2', 'product-review-manager')}
                    placeholder={__('Enter Another Text', 'product-review-manager')}
                    value={setting2}
                    onChange={newVal =>
                        dbUpdateSetting('setting2', newVal)
                    }

                />
            </AtrcPanelRow>
        </AtrcContent>
    );
};

const Documentation = () => {
    const data = useContext(AtrcReduxContextData);

    const { lsSettings, lsSaveSettings } = data;

    return (
        <AtrcWireFrameHeaderContentFooter
            headerRowProps={{
                className: classNames(AtrcPrefix('header-docs'), 'at-m'),
            }}
            renderHeader={
                <DocsTitle
                    onClick={() => {
                        const localStorageClone = cloneDeep(lsSettings);
                        localStorageClone.bmSaDocs1 = !localStorageClone.bmSaDocs1;
                        lsSaveSettings(localStorageClone);
                    }}
                />
            }
            renderContent={
                <>
                    <AtrcPanelBody
                        className={classNames(AtrcPrefix('m-0'))}
                        title={__(
                            'FAQ Query 1?',
                            'product-review-manager'
                        )}
                        initialOpen={true}>
                        <AtrcText
                            tag='p'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                'FAQ Answer 1',
                                'product-review-manager'
                            )}
                        </AtrcText>
                    </AtrcPanelBody>
                    <AtrcPanelBody
                        title={__('FAQ Query 2?', 'product-review-manager')}
                        initialOpen={false}>
                        <AtrcText
                            tag='p'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                'FAQ Answer 2',
                                'product-review-manager'
                            )}
                        </AtrcText>
                    </AtrcPanelBody>
                </>
            }
            allowHeaderRow={false}
            allowHeaderCol={false}
            allowContentRow={false}
            allowContentCol={false}
        />
    );
};

const Settings = () => {
    const data = useContext(AtrcReduxContextData);
    const { lsSettings } = data;

    const { bmSaDocs1 } = lsSettings;

    return (
        <AtrcWireFrameHeaderContentFooter
            wrapProps={{
                className: classNames(AtrcPrefix('bg-white'), 'at-bg-cl'),
            }}
            renderHeader={
                <AtrcTitleTemplate1 title={__('Settings', 'product-review-manager')} />
            }
            renderContent={
                <AtrcWireFrameContentSidebar
                    wrapProps={{
                        allowContainer: true,
                        type: 'fluid',
                        tag: 'section',
                        className: 'at-p',
                    }}
                    renderContent={<MainContent />}
                    renderSidebar={!bmSaDocs1 ? <Documentation /> : null}
                    contentProps={{
                        contentCol: bmSaDocs1 ? 'at-col-12' : 'at-col-7',
                    }}
                    sidebarProps={{
                        sidebarCol: 'at-col-5',
                    }}
                />
            }
            allowHeaderRow={false}
            allowHeaderCol={false}
            allowContentRow={false}
            allowContentCol={false}
        />
    );
};

export default Settings;
