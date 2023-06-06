export const fromUnix = (timestamp, type = 'datetime') => {
    const date = new Date(timestamp * 1000);
    const year = date.getFullYear();
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    const seconds = date.getSeconds().toString().padStart(2, '0');

    switch (type) {
        case 'datetime':
            return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        case 'date':
            return `${year}-${month}-${day}`;
        case 'time':
            return `${hours}:${minutes}:${seconds}`;
    }
};

export const fromIso = (timestamp, type = 'datetime') => {
    const date = new Date(timestamp);
    const year = date.getFullYear();
    const month = ("0" + (date.getMonth() + 1)).slice(-2);
    const day = ("0" + date.getDate()).slice(-2);
    const hours = ("0" + date.getHours()).slice(-2);
    const minutes = ("0" + date.getMinutes()).slice(-2);
    const seconds = ("0" + date.getSeconds()).slice(-2);

    switch (type) {
        case 'datetime':
            return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        case 'date':
            return `${year}-${month}-${day}`;
        case 'time':
            return `${hours}:${minutes}:${seconds}`;
    }
};
