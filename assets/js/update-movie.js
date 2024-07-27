import Query from "./query";
import { validateMovie } from "./validate-movie";

Query('form').addEventListener('submit', (e) => validateMovie(e, true));