(function(blocks, element, components, i18n, editor) {
    const el = element.createElement;
    const { registerBlockType } = blocks;
    const { InspectorControls } = editor;
    const { PanelBody, SelectControl, TextControl, ToggleControl } = components;
    const { __ } = i18n;

    registerBlockType('dlm/download-link', {
        title: __('Download Link', 'download-link-manager'),
        icon: 'download',
        category: 'common',
        attributes: {
            downloadId: {
                type: 'number',
                default: 0
            },
            buttonText: {
                type: 'string',
                default: ''
            },
            style: {
                type: 'string',
                default: 'button'
            },
            showVersion: {
                type: 'boolean',
                default: true
            },
            showCount: {
                type: 'boolean',
                default: false
            }
        },

        edit: function(props) {
            const { attributes, setAttributes } = props;
            const { downloadId, buttonText, style, showVersion, showCount } = attributes;

            // Fetch available downloads
            const [downloads, setDownloads] = element.useState([]);

            element.useEffect(() => {
                wp.apiFetch({ path: '/wp/v2/dlm_download?per_page=100' }).then(posts => {
                    const options = posts.map(post => ({
                        label: post.title.rendered,
                        value: post.id
                    }));
                    setDownloads([{ label: __('Chọn download...', 'download-link-manager'), value: 0 }, ...options]);
                });
            }, []);

            return el(
                'div',
                { className: 'dlm-block-editor' },
                [
                    el(
                        InspectorControls,
                        { key: 'inspector' },
                        el(
                            PanelBody,
                            { title: __('Cài Đặt Download', 'download-link-manager'), initialOpen: true },
                            [
                                el(SelectControl, {
                                    key: 'download-select',
                                    label: __('Chọn Download', 'download-link-manager'),
                                    value: downloadId,
                                    options: downloads,
                                    onChange: (value) => setAttributes({ downloadId: parseInt(value) })
                                }),
                                el(TextControl, {
                                    key: 'button-text',
                                    label: __('Văn bản nút (để trống = dùng tên download)', 'download-link-manager'),
                                    value: buttonText,
                                    onChange: (value) => setAttributes({ buttonText: value })
                                }),
                                el(SelectControl, {
                                    key: 'style-select',
                                    label: __('Kiểu hiển thị', 'download-link-manager'),
                                    value: style,
                                    options: [
                                        { label: __('Nút bấm', 'download-link-manager'), value: 'button' },
                                        { label: __('Link văn bản', 'download-link-manager'), value: 'link' },
                                        { label: __('Hộp download', 'download-link-manager'), value: 'box' }
                                    ],
                                    onChange: (value) => setAttributes({ style: value })
                                }),
                                el(ToggleControl, {
                                    key: 'show-version',
                                    label: __('Hiển thị phiên bản', 'download-link-manager'),
                                    checked: showVersion,
                                    onChange: (value) => setAttributes({ showVersion: value })
                                }),
                                el(ToggleControl, {
                                    key: 'show-count',
                                    label: __('Hiển thị số lượt tải', 'download-link-manager'),
                                    checked: showCount,
                                    onChange: (value) => setAttributes({ showCount: value })
                                })
                            ]
                        )
                    ),
                    el(
                        'div',
                        {
                            key: 'preview',
                            className: 'dlm-block-preview',
                            style: {
                                padding: '20px',
                                background: '#f5f5f5',
                                borderRadius: '8px',
                                border: '2px dashed #ddd'
                            }
                        },
                        downloadId > 0 
                            ? el('div', { 
                                style: { textAlign: 'center' } 
                              }, [
                                  el('p', { key: 'icon', style: { fontSize: '48px', margin: '10px 0' } }, '📥'),
                                  el('p', { key: 'text', style: { fontWeight: 'bold' } }, 
                                     __('Download Link', 'download-link-manager')),
                                  el('p', { key: 'note', style: { fontSize: '12px', color: '#666' } }, 
                                     __('(Xem trước trong frontend)', 'download-link-manager'))
                              ])
                            : el('p', { 
                                style: { textAlign: 'center', color: '#999' } 
                              }, __('Vui lòng chọn một download từ sidebar bên phải →', 'download-link-manager'))
                    )
                ]
            );
        },

        save: function() {
            // Rendered via PHP
            return null;
        }
    });

})(
    window.wp.blocks,
    window.wp.element,
    window.wp.components,
    window.wp.i18n,
    window.wp.blockEditor
);