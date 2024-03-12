const {SectionNames} = require('../../../src/Domain/Input/SectionNames');

module.exports = (config = null) => {
    config.merge({
        [SectionNames.SCHEMA]: 'https://schemas.wp.org/trunk/theme.json',
        [SectionNames.VERSION]: 2,
        [SectionNames.TITLE]: 'Example Theme',
        [SectionNames.DESCRIPTION]: 'This is an example theme.',
        [SectionNames.SETTINGS]: {
            layout: {
                contentSize: 'var(--wp--custom--content-size)',
                wideSize: 'var(--wp--custom--wide-size)',
            },
        },
        [SectionNames.STYLES]: {},
    });
};
