function insertDataQuery(_url, _postData, callbackFunction, preprocess) {

    $.ajax({ type: "POST", url: _url, data: _postData, beforeSend: preprocess,
        success: function (_returnData) {
            parseResult(callbackFunction, _returnData);
        }
    });
}

function updateDataQuery(_url, _postData, callbackFunction, preprocess) {

    $.ajax({ type: "PUT", url: _url, data: _postData, beforeSend: preprocess,
        success: function (_returnData) {
            parseResult(callbackFunction, _returnData);
        }
    });
}

function deleteDataQuery(_url, callbackFunction, preprocess) {

    $.ajax({ type: "DELETE", url: _url, beforeSend: preprocess,
        success: function (_returnData) {
            parseResult(callbackFunction, _returnData);
        }
    });
}

function generalQuery(_url, _action, _postData, callbackFunction, preprocess, type, _headerinfo) {

    if (type == null) {
        type = "html";
    }

    $.ajax({ type: _action, url: _url, data: _postData, beforeSend: preprocess,
        headers: { "API-Key": _headerinfo },
        dataType: type,
        success: function (_returnData) {
            parseResult(callbackFunction, _returnData);
        }
    });
}

function parseResult(callbackFunction, _returnData) {

    callbackFunction(_returnData);
}


function setCookie(c_name, value, expiredays, domain) {

    var exdate = new Date();
    exdate.setDate(exdate.getDate() + expiredays);
    document.cookie = c_name + "=" + escape(value) + ((expiredays == null) ? "" : ";expires=" + exdate.toGMTString() + +";domain=" + domain + ";path=/" );
}

function eraseCookie(c_name) {
    setCookie(c_name, "", -1);
}

function getCookie(c_name) {

    var c_value = document.cookie;
    var c_start = c_value.indexOf(" " + c_name + "=");

    if (c_start == -1) {
        c_start = c_value.indexOf(c_name + "=");
    }

    if (c_start == -1) {
        c_value = null;
    } else {
        c_start = c_value.indexOf("=", c_start) + 1;
        var c_end = c_value.indexOf(";", c_start);
        if (c_end == -1) {
            c_end = c_value.length;
        }
        c_value = unescape(c_value.substring(c_start, c_end));
    }
    return c_value;
}

// YOG: page redirect to view interest detail page

function redirectpage(name, id) {

    var url = site_url + 'interests?tag=' + name;
    setCookie('InterestId', id, 1, url);
    window.location = url;
}

function pageredirection(url, name, id) {

    var regex = new RegExp('\\b' + site_url + '\\b');
    if (!regex.test(url)) {
        url = site_url + url;
    }

    if (name != null && id != null) {
        setCookie(name, id, 1, url);
    }

    window.location = url;
}

function compareDate(DateValue1, DateValue2) {


    var firstValue = DateValue1.split('-');
    var secondValue = DateValue2.split('-');

    var firstDate = new Date();
    firstDate.setFullYear(firstValue[0], (firstValue[1] - 1 ), firstValue[2]);

    var secondDate = new Date();
    secondDate.setFullYear(secondValue[0], (secondValue[1] - 1 ), secondValue[2]);

    if (firstDate > secondDate) {

        return true;

    } else {

        return false;
    }

    return false;
}

function ImageExist(url) {
    var img = new Image();
    img.src = url;
    return img.height != 0;
}

function hexc(colorval) {
    var parts = colorval.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    delete(parts[0]);
    for (var i = 1; i <= 3; ++i) {
        parts[i] = parseInt(parts[i]).toString(16);
        if (parts[i].length == 1) parts[i] = '0' + parts[i];
    }
    return '#' + parts.join('');
}


function rawurlencode(str) {

    str = (str + '').toString();

    // Tilde should be allowed unescaped in future versions of PHP (as reflected below), but if you want to reflect current
    // PHP behavior, you would need to add ".replace(/~/g, '%7E');" to the following.
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').
        replace(/\)/g, '%29').replace(/\*/g, '%2A');
}

function serialize(mixed_value) {

    var val, key, okey,
        ktype = '', vals = '', count = 0,
        _utf8Size = function (str) {
            var size = 0,
                i = 0,
                l = str.length,
                code = '';
            for (i = 0; i < l; i++) {
                code = str.charCodeAt(i);
                if (code < 0x0080) {
                    size += 1;
                }
                else if (code < 0x0800) {
                    size += 2;
                }
                else {
                    size += 3;
                }
            }
            return size;
        },
        _getType = function (inp) {
            var match, key, cons, types, type = typeof inp;

            if (type === 'object' && !inp) {
                return 'null';
            }
            if (type === 'object') {
                if (!inp.constructor) {
                    return 'object';
                }
                cons = inp.constructor.toString();
                match = cons.match(/(\w+)\(/);
                if (match) {
                    cons = match[1].toLowerCase();
                }
                types = ['boolean', 'number', 'string', 'array'];
                for (key in types) {
                    if (cons == types[key]) {
                        type = types[key];
                        break;
                    }
                }
            }
            return type;
        },
        type = _getType(mixed_value)
        ;

    switch (type) {
        case 'function':
            val = '';
            break;
        case 'boolean':
            val = 'b:' + (mixed_value ? '1' : '0');
            break;
        case 'number':
            val = (Math.round(mixed_value) == mixed_value ? 'i' : 'd') + ':' + mixed_value;
            break;
        case 'string':
            val = 's:' + _utf8Size(mixed_value) + ':"' + mixed_value + '"';
            break;
        case 'array':
        case 'object':
            val = 'a';
            /*
             if (type === 'object') {
             var objname = mixed_value.constructor.toString().match(/(\w+)\(\)/);
             if (objname == undefined) {
             return;
             }
             objname[1] = this.serialize(objname[1]);
             val = 'O' + objname[1].substring(1, objname[1].length - 1);
             }
             */

            for (key in mixed_value) {
                if (mixed_value.hasOwnProperty(key)) {
                    ktype = _getType(mixed_value[key]);
                    if (ktype === 'function') {
                        continue;
                    }

                    okey = (key.match(/^[0-9]+$/) ? parseInt(key, 10) : key);
                    vals += this.serialize(okey) + this.serialize(mixed_value[key]);
                    count++;
                }
            }
            val += ':' + count + ':{' + vals + '}';
            break;
        case 'undefined':
        // Fall-through
        default:
            // if the JS object has a property which contains a null value, the string cannot be unserialized by PHP
            val = 'N';
            break;
    }
    if (type !== 'object' && type !== 'array') {
        val += ';';
    }
    return val;
}


function unserialize(data) {

    var that = this,
        utf8Overhead = function (chr) {
            // http://phpjs.org/functions/unserialize:571#comment_95906
            var code = chr.charCodeAt(0);
            if (code < 0x0080) {
                return 0;
            }
            if (code < 0x0800) {
                return 1;
            }
            return 2;
        },
        error = function (type, msg, filename, line) {
            throw new that.window[type](msg, filename, line);
        },
        read_until = function (data, offset, stopchr) {
            var i = 2, buf = [], chr = data.slice(offset, offset + 1);

            while (chr != stopchr) {
                if ((i + offset) > data.length) {
                    error('Error', 'Invalid');
                }
                buf.push(chr);
                chr = data.slice(offset + (i - 1), offset + i);
                i += 1;
            }
            return [buf.length, buf.join('')];
        },
        read_chrs = function (data, offset, length) {
            var i, chr, buf;

            buf = [];
            for (i = 0; i < length; i++) {
                chr = data.slice(offset + (i - 1), offset + i);
                buf.push(chr);
                length -= utf8Overhead(chr);
            }
            return [buf.length, buf.join('')];
        },
        _unserialize = function (data, offset) {
            var dtype, dataoffset, keyandchrs, keys, contig,
                length, array, readdata, readData, ccount,
                stringlength, i, key, kprops, kchrs, vprops,
                vchrs, value, chrs = 0,
                typeconvert = function (x) {
                    return x;
                };

            if (!offset) {
                offset = 0;
            }
            dtype = (data.slice(offset, offset + 1)).toLowerCase();

            dataoffset = offset + 2;

            switch (dtype) {
                case 'i':
                    typeconvert = function (x) {
                        return parseInt(x, 10);
                    };
                    readData = read_until(data, dataoffset, ';');
                    chrs = readData[0];
                    readdata = readData[1];
                    dataoffset += chrs + 1;
                    break;
                case 'b':
                    typeconvert = function (x) {
                        return parseInt(x, 10) !== 0;
                    };
                    readData = read_until(data, dataoffset, ';');
                    chrs = readData[0];
                    readdata = readData[1];
                    dataoffset += chrs + 1;
                    break;
                case 'd':
                    typeconvert = function (x) {
                        return parseFloat(x);
                    };
                    readData = read_until(data, dataoffset, ';');
                    chrs = readData[0];
                    readdata = readData[1];
                    dataoffset += chrs + 1;
                    break;
                case 'n':
                    readdata = null;
                    break;
                case 's':
                    ccount = read_until(data, dataoffset, ':');
                    chrs = ccount[0];
                    stringlength = ccount[1];
                    dataoffset += chrs + 2;

                    readData = read_chrs(data, dataoffset + 1, parseInt(stringlength, 10));
                    chrs = readData[0];
                    readdata = readData[1];
                    dataoffset += chrs + 2;
                    if (chrs != parseInt(stringlength, 10) && chrs != readdata.length) {
                        error('SyntaxError', 'String length mismatch');
                    }
                    break;
                case 'a':
                    readdata = {};

                    keyandchrs = read_until(data, dataoffset, ':');
                    chrs = keyandchrs[0];
                    keys = keyandchrs[1];
                    dataoffset += chrs + 2;

                    length = parseInt(keys, 10);
                    contig = true;

                    for (i = 0; i < length; i++) {
                        kprops = _unserialize(data, dataoffset);
                        kchrs = kprops[1];
                        key = kprops[2];
                        dataoffset += kchrs;

                        vprops = _unserialize(data, dataoffset);
                        vchrs = vprops[1];
                        value = vprops[2];
                        dataoffset += vchrs;

                        if (key !== i)
                            contig = false;

                        readdata[key] = value;
                    }

                    if (contig) {
                        array = new Array(length);
                        for (i = 0; i < length; i++)
                            array[i] = readdata[i];
                        readdata = array;
                    }

                    dataoffset += 1;
                    break;
                default:
                    error('SyntaxError', 'Unknown / Unhandled data type(s): ' + dtype);
                    break;
            }
            return [dtype, dataoffset - offset, typeconvert(readdata)];
        }
        ;

    return _unserialize((data + ''), 0)[2];
}