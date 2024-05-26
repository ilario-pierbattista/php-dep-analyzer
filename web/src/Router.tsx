import { Route, Routes } from "react-router";
import { BrowserRouter } from "react-router-dom";
import { Index, NotFound } from "./pages";
import { Graph } from "./pages/graph";

export function Router() {
    return (<BrowserRouter>
        <Routes>
            <Route path='/' element={<Index />} />
            <Route path='/graph' element={<Graph />} />
            <Route path='*' element={<NotFound />}></Route>
        </Routes>
    </BrowserRouter>)
}