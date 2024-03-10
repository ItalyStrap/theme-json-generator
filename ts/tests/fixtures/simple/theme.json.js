const {SectionNames} = require('../../../src/Domain/Input/SectionNames');

module.exports = (config = null) => {
    config.merge({
        [SectionNames.SCHEMA]: 'https://schemas.wp.org/trunk/theme.json',
        [SectionNames.VERSION]: 2,
        [SectionNames.SETTINGS]: {},
        [SectionNames.STYLES]: {},
    });
}