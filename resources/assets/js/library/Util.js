export default class Util{
    static capacityUnit(value, i = 0){
        value = parseInt(value);
        value = (value < 1024) ? value : value / 1024;
        if(value >= 1024){
            return this.capacityUnit(value, ++i)
        }else{
            i++
            return value.toFixed(2) + ['bytes', 'Kb', 'Mb', 'Gb', 'Tb', '', '', ''][i]
        }
    }
}