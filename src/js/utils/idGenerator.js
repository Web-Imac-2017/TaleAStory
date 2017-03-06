let lastID = 0;
export default function(prefix='id') {
    lastID++;
    return `${prefix}${lastID}`;
}
