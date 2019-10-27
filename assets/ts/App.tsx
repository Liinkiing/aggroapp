import React from 'react'
import {Link, Location, Router} from "@reach/router";
import {AnimatePresence, motion} from "framer-motion";
import Route from './components/Route'
import HomeView from './views/HomeView'
import AboutView from './views/AboutView'
import NotFoundView from './views/NotFoundView';

const FramerRouter: React.FC = ({children}) => (
    <Location>
        {({location}) => (
            <div style={{position: "relative", marginTop: '40px'}}>
                <AnimatePresence exitBeforeEnter>
                    <motion.div
                        key={location.key}
                        initial={{opacity: 0, x: -20}}
                        animate={{opacity: 1, x: 0}}
                        exit={{opacity: 0, x: -10}}
                    >
                        <Router location={location}>
                            {children}
                        </Router>
                    </motion.div>
                </AnimatePresence>
            </div>
        )}
    </Location>
);

const App: React.FC = () => (
    <div>
        <ul id="site-nav">
            <li>
                <Link to="/">Home</Link>
            </li>
            <li>
                <Link to="/about">About</Link>
            </li>
        </ul>
        <React.Suspense fallback={<div>Loading...</div>}>
            <FramerRouter>
                <Route component={HomeView} path="/"/>
                <Route component={AboutView} path="/about"/>
                <Route component={NotFoundView} default/>
            </FramerRouter>
        </React.Suspense>

    </div>
)

export default App
