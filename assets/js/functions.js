
export function convertTimeFormat(timeString) {
    var timeComponents = timeString.split(':');

    return timeComponents[0] + ':' + timeComponents[1];
}

export function padZero(number) {
    return (number < 10 ? '0' : '') + number;
}