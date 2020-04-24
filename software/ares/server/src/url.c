char from_hex(char c)
{
  return isdigit(c)? c - '0' : tolower(c) - 'a'+10;
}

char to_hex(char nb)
{
  return "0123456789abcdef"[nb&15];
}

char *url_decode(char *str)
{
    char *pstr = str, *buf = malloc(strlen(str) + 1), *pbuf = buf;
    while (*pstr)
    {
        if (*pstr == '%')
        {
            if (pstr[1] && pstr[2])
            {
                *pbuf++ = from_hex(pstr[1]) << 4 | from_hex(pstr[2]);
                pstr += 2;
            }
        }
        else if (*pstr == '+')
            *pbuf++ = ' ';
        else
            *pbuf++ = *pstr;
        pstr++;
    }
    *pbuf = '\0';
    return buf;
}
