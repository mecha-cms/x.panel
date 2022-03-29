import option from '_/field/option';
import query from '_/field/query';
import source from '_/field/source';

function onChange(init) {
    option(init);
    query(init);
    source(init);
}

export default onChange;