/* Terminal colors (16 first used in escape sequence) */
static const char *colorname[] = {

  /* 8 normal colors */
  [0] = "{{>0}}", /* black   */
  [1] = "{{>1}}", /* red     */
  [2] = "{{>2}}", /* green   */
  [3] = "{{>3}}", /* yellow  */
  [4] = "{{>4}}", /* blue    */
  [5] = "{{>5}}", /* magenta */
  [6] = "{{>6}}", /* cyan    */
  [7] = "{{>7}}", /* white   */

  /* 8 bright colors */
  [8]  = "{{>8}}", /* black   */
  [9]  = "{{>9}}", /* red     */
  [10] = "{{>10}}", /* green   */
  [11] = "{{>11}}", /* yellow  */
  [12] = "{{>12}}", /* blue    */
  [13] = "{{>13}}, /* magenta */
  [14] = "{{>14}}", /* cyan    */
  [15] = "{{>15}}", /* white   */

  /* special colors */
  [256] = "{{>background}}", /* background */
  [257] = "{{>foreground}}", /* foreground */
};

/*
 * Default colors (colorname index)
 * foreground, background, cursor
 */
static unsigned int defaultfg = 257;
static unsigned int defaultbg = 256;
static unsigned int defaultcs = 257;

/*
 * Colors used, when the specific fg == defaultfg. So in reverse mode this
 * will reverse too. Another logic would only make the simple feature too
 * complex.
 */
static unsigned int defaultitalic = 7;
static unsigned int defaultunderline = 7;
